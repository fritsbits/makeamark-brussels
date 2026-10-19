<?php

namespace Tests\Feature;

use App\Content\Homepage;
use App\Mail\ApplicationReceived;
use App\Models\Application;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class OrganisationSubmissionHardeningTest extends TestCase
{
    use RefreshDatabase;

    public static function malformedAnswers(): array
    {
        return [
            'scalar languages' => [['languages' => 'English'], 'languages'],
            'nested languages' => [['languages' => [['nested']]], 'languages.0'],
            'duplicate languages' => [['languages' => ['English', 'English']], 'languages.0'],
            'too many languages' => [['languages' => ['English', 'Dutch', 'French', 'English']], 'languages'],
            'unknown language' => [['languages' => ['Spanish']], 'languages.0'],
            'array name' => [['organisation_name' => ['nested']], 'organisation_name'],
            'array mission' => [['mission' => ['nested']], 'mission'],
            'array optional text' => [['social_media' => ['nested']], 'social_media'],
            'array contact' => [['contact_name' => ['nested']], 'contact_name'],
        ];
    }

    #[DataProvider('malformedAnswers')]
    public function test_malformed_answers_return_a_renderable_form(array $changes, string $error): void
    {
        $answers = array_replace($this->answers(), $changes);
        $answers['extra_secret'] = 'do not flash';
        $this->post('/organisations/apply', $answers)->assertRedirect('/organisations#application')
            ->assertSessionHasErrors($error)->assertSessionMissing('_old_input.extra_secret');
        $this->get('/organisations')->assertOk()->assertSee('Some details need your attention.');
        $this->assertDatabaseCount('applications', 0);
    }

    public function test_replaying_a_form_saves_and_sends_its_receipt_once(): void
    {
        Mail::fake();
        config(['mail.default' => 'resend', 'services.resend.key' => 'fixture', 'mail.from.address' => 'team@example.com']);
        $answers = $this->answers();
        $this->post('/organisations/apply', $answers)->assertRedirect('/organisations#application')->assertSessionHasNoErrors();
        $answers['email'] = 'different@example.com';
        $this->post('/organisations/apply', $answers)->assertRedirect('/organisations#application')->assertSessionHasNoErrors();
        $this->assertDatabaseCount('applications', 1);
        $this->assertDatabaseHas('applications', ['email' => 'organisation@example.com']);
        Mail::assertSent(ApplicationReceived::class, 1);
        $this->get('/organisations')->assertOk()->assertSee('Application received')->assertSee('A receipt email has been sent');
    }

    public function test_an_old_brussels_confirmation_does_not_replace_the_youth_confirmation(): void
    {
        $answers = $this->answers();
        unset($answers['youth_brussels_eligibility']);
        $answers['brussels_eligibility'] = '1';

        $this->post('/organisations/apply', $answers)
            ->assertRedirect('/organisations#application')
            ->assertSessionHasErrors('youth_brussels_eligibility')
            ->assertSessionHasInput('youth_brussels_eligibility', '');

        $this->assertDatabaseCount('applications', 0);
        $this->get('/organisations')->assertOk()
            ->assertSee('Please confirm that your organisation works with young people in Brussels.');
    }

    public function test_an_unchecked_youth_confirmation_is_rejected(): void
    {
        $answers = $this->answers();
        $answers['youth_brussels_eligibility'] = '0';

        $this->post('/organisations/apply', $answers)
            ->assertSessionHasErrors('youth_brussels_eligibility');

        $this->assertDatabaseCount('applications', 0);
    }

    public function test_new_eligibility_is_saved_without_turning_legacy_answers_into_youth_answers(): void
    {
        config(['mail.default' => 'array']);
        $answers = $this->answers();
        $answers['brussels_eligibility'] = '1';

        $this->post('/organisations/apply', $answers)->assertSessionHasNoErrors();

        $stored = Application::sole()->answers;
        $this->assertSame('1', $stored['youth_brussels_eligibility']);
        $this->assertArrayNotHasKey('brussels_eligibility', $stored);
    }

    public function test_tokens_are_required_and_bound_to_both_the_audience_and_session(): void
    {
        $answers = $this->answers();
        unset($answers['_submission_token']);
        $this->post('/organisations/apply', $answers)->assertRedirect('/organisations#application')->assertSessionHasErrors('submission');

        $answers = $this->answers();
        $answers['_submission_token'] = $this->get('/makers')->viewData('submissionToken');
        $this->post('/organisations/apply', $answers)->assertRedirect('/organisations#application')->assertSessionHasErrors('submission');

        $answers = $this->answers();
        $this->withSession(['organisation_submission_secret' => str_repeat('z', 64)]);
        session()->save();
        $this->post('/organisations/apply', $answers)->assertRedirect('/organisations#application')->assertSessionHasErrors('submission');
        $this->assertDatabaseCount('applications', 0);
    }

    public function test_distinct_tabs_keep_independent_tokens_and_applications(): void
    {
        $first = $this->answers();
        $second = $this->answers();
        $this->assertNotSame($first['_submission_token'], $second['_submission_token']);
        $this->post('/organisations/apply', $first)->assertSessionHasNoErrors();
        $this->post('/organisations/apply', $second)->assertSessionHasNoErrors();
        $this->assertDatabaseCount('applications', 2);
    }

    public function test_organisation_token_cannot_be_used_for_makers_even_if_secrets_match(): void
    {
        $organisation = $this->answers();
        $this->withSession(['maker_submission_secret' => session('organisation_submission_secret')]);
        session()->save();
        $maker = [
            '_submission_token' => $organisation['_submission_token'],
            'name' => 'Test Maker', 'email' => 'maker@example.com',
            'motivation' => 'Contribute to a local project.', 'portfolio' => 'https://example.com/work',
            'main_skills' => 'Design', 'date_confirmed' => '1',
            'meeting_availability' => 'Weekday evenings', 'full_commitment' => '1',
        ];
        $this->post('/makers/apply', $maker)->assertRedirect('/makers#application')->assertSessionHasErrors('submission');
        $this->assertDatabaseCount('applications', 0);
    }

    public function test_failed_receipt_delivery_still_confirms_saved_application_without_claiming_email_sent(): void
    {
        config(['mail.default' => 'resend', 'services.resend.key' => 'fixture', 'mail.from.address' => 'team@example.com']);
        Mail::shouldReceive('mailer')->with('resend')->once()->andThrow(new \RuntimeException('fixture provider failure'));
        $answers = $this->answers();
        $this->post('/organisations/apply', $answers)->assertRedirect('/organisations#application')->assertSessionHasNoErrors();
        $this->post('/organisations/apply', $answers)->assertRedirect('/organisations#application')->assertSessionHasNoErrors();
        $this->assertDatabaseCount('applications', 1);
        $this->assertNull(Application::sole()->receipt_sent_at);
        $this->get('/organisations')->assertOk()->assertSee('Application received')->assertDontSee('A receipt email has been sent');
    }

    public function test_rate_limiting_recovers_only_bounded_organisation_answers(): void
    {
        $answers = $this->answers();
        $answers['email'] = 'invalid';
        foreach (range(1, 5) as $attempt) {
            $this->post('/organisations/apply', $answers)->assertRedirect('/organisations#application');
        }
        $answers['organisation_name'] = 'Latest draft';
        $answers['mission'] = str_repeat('a', 6000);
        $answers['languages'] = ['English', 'Dutch', 'French', ['nested']];
        $answers['assets_ready'] = ['nested'];
        $answers['extra_secret'] = 'do not flash';
        $this->post('/organisations/apply', $answers)->assertRedirect('/organisations#application')
            ->assertSessionHasErrors('submission')->assertSessionHasInput('organisation_name', 'Latest draft')
            ->assertSessionHasInput('mission', str_repeat('a', 5000))
            ->assertSessionHasInput('languages', ['English', 'Dutch', 'French'])
            ->assertSessionHasInput('assets_ready', '')->assertSessionMissing('_old_input.extra_secret')->assertHeader('Retry-After');
        $this->get('/organisations')->assertOk()->assertSee('wait')->assertSee('Latest draft');
        $this->assertDatabaseCount('applications', 0);
    }

    public function test_expired_csrf_recovers_draft_and_allows_a_fresh_submission(): void
    {
        $answers = $this->answers();
        $answers['_token'] = 'expired';
        $answers['organisation_name'] = ['malformed'];
        session()->forget(['organisation_submission_secret', '_token']);
        session()->save();
        app()->instance('env', 'production');
        $this->post('/organisations/apply', $answers)->assertRedirect('/organisations#application')
            ->assertSessionHasErrors('submission')->assertSessionHasInput('organisation_name', '')
            ->assertSessionHasInput('mission', 'Support local youth.');
        $page = $this->get('/organisations')->assertOk()->assertSee('expired');
        $this->assertDatabaseCount('applications', 0);
        $freshToken = $page->viewData('submissionToken');
        $this->assertNotSame($answers['_submission_token'], $freshToken);
        $answers['_submission_token'] = $freshToken;
        $answers['_token'] = csrf_token();
        $answers['organisation_name'] = 'Recovered Organisation';
        $this->post('/organisations/apply', $answers)->assertRedirect('/organisations#application')->assertSessionHasNoErrors();
        $this->assertDatabaseCount('applications', 1);
        app()->instance('env', 'testing');
    }

    public function test_closed_edition_explains_the_rejection_without_saving(): void
    {
        $answers = $this->answers();
        $data = app(Homepage::class)->data();
        $data['edition']['status'] = 'closed';
        $this->mock(Homepage::class)->shouldReceive('data')->andReturn($data);
        $this->post('/organisations/apply', $answers)->assertRedirect('/organisations#application')
            ->assertSessionHasErrors('submission')->assertSessionHasInput('organisation_name', 'Test Organisation');
        $this->assertDatabaseCount('applications', 0);
    }

    public function test_maker_navigation_does_not_consume_organisation_confirmation(): void
    {
        $this->post('/organisations/apply', $this->answers())->assertSessionHasNoErrors();
        $this->get('/makers')->assertOk()->assertDontSee('Application received');
        $this->get('/organisations')->assertOk()->assertSee('Application received');
        $this->get('/organisations')->assertOk()->assertSee('class="application-form"', false);
    }

    public function test_organisation_preview_issues_a_valid_submission_token(): void
    {
        $answers = $this->answers();
        $answers['_submission_token'] = $this->get('/tools/messaging/organisations')->assertOk()->viewData('submissionToken');
        $this->assertNotEmpty($answers['_submission_token']);
        $this->post('/organisations/apply', $answers)->assertRedirect('/organisations#application')->assertSessionHasNoErrors();
        $this->assertDatabaseCount('applications', 1);
    }

    private function answers(): array
    {
        $page = $this->withoutVite()->get('/organisations')->assertOk();
        $this->withCookie(config('session.cookie'), session()->getId());
        $this->assertNotEmpty($page->viewData('submissionToken'));

        return [
            '_submission_token' => $page->viewData('submissionToken'),
            'organisation_name' => 'Test Organisation', 'email' => 'organisation@example.com',
            'youth_brussels_eligibility' => '1', 'mission' => 'Support local youth.',
            'challenge' => 'Improve our communication.', 'contact_name' => 'Test Contact',
            'phone' => '0123456789', 'languages' => ['English', 'Dutch', 'French'], 'assets_ready' => '1',
            'planning_meeting' => '1', 'feedback_session' => '1', 'evening_presentation' => '1',
        ];
    }
}
