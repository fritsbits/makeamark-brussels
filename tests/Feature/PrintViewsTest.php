<?php

namespace Tests\Feature;

use App\Filament\Resources\OrganisationResource\Pages\ListOrganisations;
use App\Filament\Resources\TeamResource\Pages\ListTeams;
use App\Models\Application;
use App\Models\Team;
use App\Models\User;
use Filament\Facades\Filament;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class PrintViewsTest extends TestCase
{
    use RefreshDatabase;

    public function test_the_team_briefing_prints_the_brief_without_contact_details(): void
    {
        $organisation = $this->organisation();
        $team = Team::create(['name' => 'Team Zinneke', 'edition_year' => 2027, 'organisation_application_id' => $organisation->id]);
        $this->actingAs($this->admin());

        foreach ([route('filament.admin.print.team', $team), route('filament.admin.print.organisation', $organisation)] as $url) {
            $this->withoutVite()->get($url)->assertOk()
                ->assertSee('Zinneke Youth House')
                ->assertSee('Help us reach teenagers who left school.')
                ->assertSee('Graphic design')
                ->assertSee('Education and learning')
                ->assertSee('French')
                ->assertDontSee('Dutch')
                ->assertSee('Print briefing')
                ->assertDontSee('Marie Contact')
                ->assertDontSee('marie@zinneke.example')
                ->assertDontSee('0470 12 34 56')
                ->assertDontSee('Youth communication challenge');
        }
    }

    public function test_the_briefing_is_for_active_organisers_and_organisations_only(): void
    {
        $organisation = $this->organisation();
        $maker = Application::create(['audience' => 'makers', 'edition_year' => 2027, 'contact_name' => 'A Maker', 'email' => 'maker@example.com', 'answers' => []]);
        $url = route('filament.admin.print.organisation', $organisation);

        $this->get($url)->assertRedirect('/admin/login');
        $this->actingAs(User::factory()->create())->get($url)->assertForbidden();
        $this->actingAs($this->admin())->get(route('filament.admin.print.organisation', $maker))->assertNotFound();
    }

    public function test_print_briefing_actions_are_offered_in_the_admin(): void
    {
        $organisation = $this->organisation();
        $team = Team::create(['name' => 'Team Zinneke', 'edition_year' => 2027, 'organisation_application_id' => $organisation->id]);
        $this->actingAs($this->admin());
        Filament::setCurrentPanel(Filament::getPanel('admin'));

        Livewire::test(ListOrganisations::class)->assertTableActionVisible('printBriefing', $organisation);
        Livewire::test(ListTeams::class)->assertTableActionVisible('printBriefing', $team);
    }

    public function test_an_organisation_can_print_its_own_answers_after_submitting(): void
    {
        $organisation = $this->organisation();

        $this->withSession(['application_confirmations.organisations' => ['email_sent' => false, 'application_id' => $organisation->id, 'expires_at' => time() + 600]])
            ->withoutVite()->get('/organisations')->assertOk()
            ->assertSee('Application received')
            ->assertSee('Print your answers')
            ->assertSee('Zinneke Youth House')
            ->assertSee('Youth communication challenge')
            ->assertSee('Marie Contact')
            ->assertSee('marie@zinneke.example')
            ->assertSee('0470 12 34 56');
    }

    public function test_answers_are_only_shown_for_the_organisation_confirmation(): void
    {
        $this->withEditionStatus('preparing');
        $maker = Application::create(['audience' => 'makers', 'edition_year' => 2027, 'contact_name' => 'A Maker', 'email' => 'maker@example.com', 'answers' => ['motivation' => 'Secret motivation']]);

        $this->withSession(['application_confirmations.makers' => ['email_sent' => false, 'application_id' => $maker->id, 'expires_at' => time() + 600]])
            ->withoutVite()->get('/makers')->assertOk()->assertSee('Application received')
            ->assertDontSee('Print your answers')->assertDontSee('Secret motivation');

        $this->withoutVite()->get('/organisations')->assertOk()->assertDontSee('Print your answers');
    }

    public function test_a_submitted_organisation_application_leads_to_the_printable_answers(): void
    {
        config(['mail.default' => 'log']);
        $answers = [
            'organisation_name' => 'Flow Organisation', 'email' => 'flow@example.com', 'youth_brussels_eligibility' => '1',
            'mission' => 'Support local youth.', 'challenge' => 'Improve our communication.', 'contact_name' => 'Flow Contact',
            'phone' => '0123456789', 'organisation_languages' => ['Dutch'], 'languages' => ['English'], 'assets_ready' => '1',
            'planning_meeting' => '1', 'feedback_session' => '1', 'evening_presentation' => '1',
        ];
        $answers['_submission_token'] = $this->withoutVite()->get('/organisations')->viewData('submissionToken');
        $this->withCookie(config('session.cookie'), session()->getId());

        $this->post('/organisations/apply', $answers)->assertRedirect('/organisations#application');

        $this->withoutVite()->get('/organisations')->assertOk()
            ->assertSee('Print your answers')->assertSee('Flow Organisation')->assertSee('Improve our communication.');
        $this->get('/organisations')->assertDontSee('Print your answers');
    }

    private function organisation(): Application
    {
        return Application::create([
            'audience' => 'organisations', 'edition_year' => 2027,
            'contact_name' => 'Marie Contact', 'email' => 'marie@zinneke.example',
            'refined_brief' => 'Help us reach teenagers who left school.',
            'skills_needed' => ['graphic-design'],
            'answers' => [
                'organisation_name' => 'Zinneke Youth House', 'challenge' => 'Youth communication challenge',
                'contact_name' => 'Marie Contact', 'email' => 'marie@zinneke.example', 'phone' => '0470 12 34 56',
                'organisation_languages' => ['French'], 'languages' => ['Dutch'], 'themes' => ['Education and learning'],
            ],
        ]);
    }

    private function admin(): User
    {
        return User::factory()->create(['is_admin' => true, 'is_active' => true]);
    }
}
