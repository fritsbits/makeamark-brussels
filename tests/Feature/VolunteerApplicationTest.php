<?php

namespace Tests\Feature;

use App\Filament\Resources\VolunteerResource\Pages\EditVolunteer;
use App\Mail\ApplicationReceived;
use App\Models\Application;
use App\Models\User;
use App\Support\Audiences;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use InvalidArgumentException;
use Livewire\Livewire;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class VolunteerApplicationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Volunteers apply while Maker and organisation applications are still being prepared.
        $this->withEditionStatus('preparing');
        $this->travelTo('2026-11-07 12:00:00');
    }

    public function test_the_registry_knows_three_audiences_and_rejects_unknown_ones(): void
    {
        $this->assertSame(['makers', 'organisations', 'volunteers'], Audiences::slugs());
        $this->expectException(InvalidArgumentException::class);
        Audiences::find('sponsors');
    }

    public function test_the_page_explains_the_roles_and_offers_the_form_while_makers_wait(): void
    {
        $this->get('/volunteers')->assertOk()
            ->assertSee('<title>For volunteers · Make a Mark Brussels</title>', false)
            ->assertSee('Capture a day of creative work for Brussels.')
            ->assertSeeInOrder(['Photographer', 'Videographer', 'Social media manager'])
            ->assertSee('Apply as a Maker')
            ->assertSee('Volunteer application')
            ->assertSee('Send volunteer application')
            ->assertDontSee('name="credit_as"', false);

        $this->get('/makers')->assertOk()->assertDontSee('Send Maker application');
        $this->get('/volunteers/apply')->assertRedirect(route('audience.show', 'volunteers').'#application');
    }

    public function test_navigation_homepage_and_sitemap_link_to_the_page(): void
    {
        $this->get('/')->assertOk()
            ->assertSee('For volunteers')
            ->assertSee("take home work you'll want to show", false)
            ->assertSee('href="/volunteers"', false);
        $this->get('/sitemap-0.xml')->assertOk()->assertSee('/volunteers</loc>', false);
    }

    public static function closedMoments(): array
    {
        return [
            'day after the event' => ['2027-03-28 09:00:00', 'open'],
            'edition done' => ['2026-11-07 12:00:00', 'done'],
        ];
    }

    #[DataProvider('closedMoments')]
    public function test_applications_close_after_the_event(string $moment, string $status): void
    {
        $answers = $this->answers();
        $this->withEditionStatus($status);
        $this->travelTo($moment);

        $this->get('/volunteers')->assertOk()
            ->assertSee('Volunteer applications are closed for 2027')
            ->assertDontSee('Send volunteer application');
        $this->post('/volunteers/apply', $answers)->assertRedirect('/volunteers#application')
            ->assertSessionHasErrors('submission');
        $this->assertDatabaseCount('applications', 0);
    }

    public function test_applications_stay_open_on_the_event_day(): void
    {
        $this->travelTo('2027-03-27 20:00:00');
        $this->post('/volunteers/apply', $this->answers())->assertSessionHasNoErrors();
        $this->assertDatabaseCount('applications', 1);
    }

    public function test_a_valid_application_is_saved_with_its_roles_and_a_volunteer_receipt(): void
    {
        Mail::fake();
        config(['mail.default' => 'resend', 'services.resend.key' => 'fixture', 'mail.from.address' => 'team@example.com']);

        $this->post('/volunteers/apply', $this->answers())->assertRedirect('/volunteers#application')->assertSessionHasNoErrors();

        $application = Application::sole();
        $this->assertSame('volunteers', $application->audience);
        $this->assertSame('Vera Volunteer', $application->contact_name);
        $this->assertSame(['photographer', 'social_media'], $application->roles);
        $this->assertSame([], $application->themes);
        $this->assertNull($application->assigned_roles);
        $this->assertSame(['English', 'French'], $application->answers['languages']);
        Mail::assertSent(ApplicationReceived::class, fn (ApplicationReceived $mail): bool => $mail->audience === 'volunteers'
            && str_contains($mail->render(), 'your volunteer application'));

        $this->get('/volunteers')->assertOk()
            ->assertSee('Thanks for offering to capture the day.')
            ->assertSee('Your volunteer application has been saved for the 2027 edition.');
    }

    public function test_a_replayed_form_saves_once(): void
    {
        $answers = $this->answers();
        $this->post('/volunteers/apply', $answers)->assertSessionHasNoErrors();
        $this->post('/volunteers/apply', [...$answers, 'email' => 'other@example.com'])->assertSessionHasNoErrors();
        $this->assertDatabaseCount('applications', 1);
    }

    public function test_languages_are_only_asked_of_social_media_managers(): void
    {
        $this->post('/volunteers/apply', [...$this->answers(), 'languages' => []])->assertSessionHasErrors('languages');
        $this->get('/volunteers')->assertSee('Choose at least one language you can post in.');

        $this->post('/volunteers/apply', [...$this->answers(), 'roles' => ['videographer'], 'languages' => ['English']])->assertSessionHasNoErrors();
        $this->assertArrayNotHasKey('languages', Application::sole()->answers);
    }

    public static function invalidAnswers(): array
    {
        return [
            'no roles' => [['roles' => []], 'roles'],
            'scalar roles' => [['roles' => 'photographer'], 'roles'],
            'unknown role' => [['roles' => ['case_study_writer']], 'roles.0'],
            'duplicate role' => [['roles' => ['photographer', 'photographer']], 'roles.0'],
            'no day parts' => [['day_parts' => []], 'day_parts'],
            'unknown day part' => [['day_parts' => ['night']], 'day_parts.0'],
            'unconfirmed date' => [['date_confirmed' => '0'], 'date_confirmed'],
            'no mobile number' => [['phone' => ''], 'phone'],
            'portfolio not a URL' => [['portfolio' => 'my instagram'], 'portfolio'],
            'unknown language' => [['languages' => ['Spanish']], 'languages.0'],
        ];
    }

    #[DataProvider('invalidAnswers')]
    public function test_invalid_answers_return_to_the_form(array $changes, string $error): void
    {
        $this->post('/volunteers/apply', [...$this->answers(), ...$changes])
            ->assertRedirect('/volunteers#application')->assertSessionHasErrors($error);
        $this->get('/volunteers')->assertOk()->assertSee('Some details need your attention.');
        $this->assertDatabaseCount('applications', 0);
    }

    public function test_organisers_assign_roles_and_see_who_also_applied_as_maker(): void
    {
        $volunteer = Application::create(['audience' => 'volunteers', 'edition_year' => 2027, 'contact_name' => 'Vera', 'email' => 'Vera@Example.com',
            'answers' => ['name' => 'Vera', 'email' => 'Vera@Example.com', 'roles' => ['photographer'], 'day_parts' => ['evening']]]);
        Application::create(['audience' => 'makers', 'edition_year' => 2027, 'contact_name' => 'Vera', 'email' => 'vera@example.com', 'answers' => ['name' => 'Vera'], 'status' => 'selected']);
        $other = Application::create(['audience' => 'volunteers', 'edition_year' => 2027, 'contact_name' => 'Otto', 'email' => 'otto@example.com',
            'answers' => ['name' => 'Otto', 'roles' => ['videographer']]]);
        $this->actingAs(User::factory()->create(['is_admin' => true, 'is_active' => true]));

        $this->get('/admin/volunteers')->assertOk()->assertSee('Volunteers')->assertSee('Vera')->assertSee('Otto');
        $this->get('/admin/volunteers/'.$volunteer->id.'/edit')->assertOk()->assertSee('Also applied as Maker')->assertSee('2027 · Selected');
        $this->get('/admin/volunteers/'.$other->id.'/edit')->assertOk()->assertDontSee('Also applied as Maker');

        Livewire::test(EditVolunteer::class, ['record' => $volunteer->id])
            ->fillForm(['status' => 'selected', 'assigned_roles' => ['photographer', 'videographer']])
            ->call('save')->assertHasNoFormErrors();

        $volunteer->refresh();
        $this->assertSame('selected', $volunteer->status);
        $this->assertSame(['photographer', 'videographer'], $volunteer->assigned_roles);
        $this->assertSame(['photographer'], $volunteer->roles);
        $this->get('/admin/makers/'.$volunteer->id.'/edit')->assertNotFound();
    }

    private function answers(): array
    {
        $page = $this->withoutVite()->get('/volunteers')->assertOk();
        $this->withCookie(config('session.cookie'), session()->getId());
        $this->assertNotEmpty($page->viewData('submissionToken'));

        return [
            '_submission_token' => $page->viewData('submissionToken'),
            'name' => 'Vera Volunteer', 'email' => 'vera@example.com', 'phone' => '0470000000',
            'roles' => ['photographer', 'social_media'], 'portfolio' => 'https://example.com/vera',
            'role_notes' => 'Two camera bodies.', 'languages' => ['English', 'French'],
            'date_confirmed' => '1', 'day_parts' => ['daytime', 'evening'],
        ];
    }
}
