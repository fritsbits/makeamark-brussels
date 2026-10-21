<?php

namespace Tests\Feature;

use App\Content\Homepage;
use App\Mail\ApplicationReceived;
use App\Models\Application;
use Illuminate\Database\UniqueConstraintViolationException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class MakerSubmissionHardeningTest extends TestCase
{
    use RefreshDatabase;

    public static function malformedAnswers(): array
    {
        return [
            'scalar themes' => [['themes' => 'invalid'], 'themes'],
            'nested themes' => [['themes' => [['nested']]], 'themes.0'],
            'array name' => [['name' => ['nested']], 'name'],
            'array motivation' => [['motivation' => ['nested']], 'motivation'],
            'array optional text' => [['referral' => ['nested']], 'referral'],
            'array language' => [['english' => ['nested']], 'english'],
            'duplicate themes' => [['themes' => ['Culture and community building', 'Culture and community building']], 'themes.0'],
            'too many themes' => [['themes' => array_fill(0, 11, 'Culture and community building')], 'themes'],
        ];
    }

    #[DataProvider('malformedAnswers')]
    public function test_malformed_answers_return_a_renderable_form(array $changes, string $error): void
    {
        $answers = array_replace($this->answers(), $changes);
        $this->post('/makers/apply', $answers)->assertRedirect('/makers#application')->assertSessionHasErrors($error);
        $this->get('/makers')->assertOk()->assertSee('Some details need your attention.');
        $this->assertDatabaseCount('applications', 0);
    }

    public function test_replaying_a_form_saves_and_sends_its_receipt_once(): void
    {
        Mail::fake();
        config(['mail.default' => 'resend', 'services.resend.key' => 'fixture', 'mail.from.address' => 'team@example.com']);
        $answers = $this->answers();
        $this->post('/makers/apply', $answers)->assertRedirect('/makers#application');
        $this->post('/makers/apply', $answers)->assertRedirect('/makers#application');
        $this->assertDatabaseCount('applications', 1);
        Mail::assertSent(ApplicationReceived::class, 1);
        $this->get('/makers')->assertOk()->assertSee('Application received')->assertSee('A receipt email has been sent');
    }

    public function test_maker_forms_issued_before_shared_form_extraction_keep_their_replay_identity(): void
    {
        Mail::fake();
        $answers = $this->answers();
        $secret = session('maker_submission_secret');
        $nonce = str_repeat('a', 32);
        $answers['_submission_token'] = $nonce.'.'.hash_hmac('sha256', $nonce, $secret);
        $year = app(Homepage::class)->data()['edition']['year'];
        $application = Application::create([
            'audience' => 'makers', 'edition_year' => $year,
            'contact_name' => 'Original Maker', 'email' => 'original@example.com', 'answers' => [],
            'submission_key' => hash_hmac('sha256', $year.':'.$answers['_submission_token'], $secret),
        ]);
        $this->post('/makers/apply', $answers)->assertRedirect('/makers#application')->assertSessionHasNoErrors();
        $this->assertDatabaseCount('applications', 1);
        $this->assertSame('original@example.com', $application->fresh()->email);
        Mail::assertNothingSent();
    }

    public function test_all_ten_distinct_themes_can_be_submitted(): void
    {
        $answers = $this->answers();
        $answers['themes'] = [
            'Poverty reduction and social security', 'Culture and community building',
            'Health and well-being', 'Housing and living conditions', 'Local economy and employment',
            'Environment and sustainability', 'Education and youth development',
            'Social inclusion and diversity', 'Sports and recreation', 'Refugee and migrant support',
        ];
        $this->post('/makers/apply', $answers)->assertRedirect('/makers#application')->assertSessionHasNoErrors();
        $this->assertSame($answers['themes'], Application::sole()->answers['themes']);
    }

    public function test_separate_tabs_allow_separate_applications_with_the_same_email(): void
    {
        $first = $this->answers();
        $second = $this->answers();
        $this->post('/makers/apply', $first)->assertSessionHasNoErrors();
        $this->post('/makers/apply', $second)->assertSessionHasNoErrors();
        $this->assertDatabaseCount('applications', 2);
    }

    public function test_missing_or_another_sessions_token_cannot_create_an_application(): void
    {
        $answers = $this->answers();
        unset($answers['_submission_token']);
        $this->post('/makers/apply', $answers)->assertRedirect('/makers#application')->assertSessionHasErrors('submission');
        $this->assertDatabaseCount('applications', 0);

        $answers = $this->answers();
        $this->withSession(['maker_submission_secret' => str_repeat('z', 64)]);
        session()->save();
        $this->post('/makers/apply', $answers)->assertRedirect('/makers#application')->assertSessionHasErrors('submission');
        $this->assertDatabaseCount('applications', 0);
    }

    public function test_organisation_navigation_does_not_consume_a_maker_confirmation(): void
    {
        $this->post('/makers/apply', $this->answers())->assertSessionHasNoErrors();
        $this->get('/organisations')->assertOk()->assertDontSee('Application received')->assertSee('class="application-form"', false);
        $this->get('/makers')->assertOk()->assertSee('Application received');
        $this->get('/makers')->assertOk()->assertSee('class="application-form"', false);
    }

    public function test_rate_limit_returns_safe_answers_and_an_actionable_message(): void
    {
        $answers = $this->answers();
        $answers['email'] = 'invalid';
        foreach (range(1, 5) as $attempt) {
            $this->post('/makers/apply', $answers)->assertRedirect('/makers#application');
        }
        $answers['name'] = 'Latest draft';
        $answers['extra_secret'] = 'do not flash';
        $this->post('/makers/apply', $answers)->assertRedirect('/makers#application')
            ->assertSessionHasErrors('submission')->assertSessionHasInput('name', 'Latest draft')
            ->assertSessionMissing('_old_input.extra_secret')->assertHeader('Retry-After');
        $this->get('/makers')->assertOk()->assertSee('wait')->assertSee('Latest draft');
        $this->assertDatabaseCount('applications', 0);
    }

    public function test_expired_csrf_recovers_a_safe_draft_but_never_submits_it(): void
    {
        $answers = $this->answers();
        $answers['_token'] = 'expired';
        $answers['name'] = ['malformed'];
        session()->forget(['maker_submission_secret', '_token']);
        session()->save();
        app()->instance('env', 'production');
        $this->post('/makers/apply', $answers)->assertRedirect('/makers#application')
            ->assertSessionHasErrors('submission')->assertSessionHasInput('name', '')
            ->assertSessionHasInput('motivation', 'Contribute to a local project.');
        $page = $this->get('/makers')->assertOk()->assertSee('expired');
        $this->assertDatabaseCount('applications', 0);
        $freshToken = $page->viewData('submissionToken');
        $this->assertNotSame($answers['_submission_token'], $freshToken);
        $answers['_submission_token'] = $freshToken;
        $answers['_token'] = csrf_token();
        $answers['name'] = 'Recovered Maker';
        $this->post('/makers/apply', $answers)->assertRedirect('/makers#application')->assertSessionHasNoErrors();
        $this->assertDatabaseHas('applications', ['contact_name' => 'Recovered Maker']);
        app()->instance('env', 'testing');
    }

    public function test_closed_edition_explains_the_rejection_without_saving(): void
    {
        $answers = $this->answers();
        $this->withEditionStatus('closed');
        $this->post('/makers/apply', $answers)->assertRedirect('/makers#application')
            ->assertSessionHasErrors('submission')->assertSessionHasInput('name', 'Test Maker');
        $this->assertDatabaseCount('applications', 0);
        $this->post('/organisations/apply', [])->assertRedirect('/organisations#application')->assertSessionHasErrors('submission');
    }

    public function test_preparing_edition_says_applications_are_not_open_yet(): void
    {
        $answers = $this->answers();
        $this->withEditionStatus('preparing');
        $this->post('/makers/apply', $answers)->assertRedirect('/makers#application')
            ->assertSessionHasErrors(['submission' => 'Applications aren’t open yet, so your application has not been sent. Contact the organising team if you need help.']);
        $this->assertDatabaseCount('applications', 0);
        $this->followRedirects($this->post('/makers/apply', $answers))
            ->assertSee('Applications aren’t open yet', false)
            ->assertSee('Applications open soon')
            ->assertSee('Follow us on Instagram')
            ->assertDontSee('Send maker application');
    }

    public function test_unique_submission_key_is_enforced_by_the_database(): void
    {
        $this->post('/makers/apply', $this->answers())->assertSessionHasNoErrors();
        $application = Application::firstOrFail();
        $this->assertNotEmpty($application->submission_key);
        $this->expectException(UniqueConstraintViolationException::class);
        $application->replicate()->save();
    }

    private function answers(): array
    {
        $page = $this->withoutVite()->get('/makers')->assertOk();
        $this->withCookie(config('session.cookie'), session()->getId());

        return [
            '_submission_token' => $page->original->getData()['submissionToken'] ?? 'unissued-fixture',
            'name' => 'Test Maker', 'email' => 'maker@example.com',
            'motivation' => 'Contribute to a local project.', 'portfolio' => 'https://example.com/work',
            'main_skills' => 'Design', 'date_confirmed' => '1',
            'meeting_availability' => 'Weekday evenings', 'full_commitment' => '1',
        ];
    }
}
