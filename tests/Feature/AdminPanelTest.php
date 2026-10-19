<?php

namespace Tests\Feature;

use App\Filament\Resources\MakerResource;
use App\Filament\Resources\OrganisationResource;
use App\Filament\Resources\OrganisationResource\Pages\EditOrganisation;
use App\Filament\Resources\TeamResource\Pages\CreateTeam;
use App\Filament\Resources\TeamResource\Pages\EditTeam;
use App\Models\Application;
use App\Models\Team;
use App\Models\User;
use App\Services\Matchmaking;
use Filament\Facades\Filament;
use Filament\Forms\Components\Select;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;
use Livewire\Livewire;
use Tests\TestCase;

class AdminPanelTest extends TestCase
{
    use RefreshDatabase;

    public function test_guests_must_log_in_and_public_users_cannot_access_applications(): void
    {
        $this->get('/admin/organisations')->assertRedirect('/admin/login');
        $this->actingAs(User::factory()->create())->get('/admin/organisations')->assertForbidden();
    }

    public function test_active_organisers_can_open_the_workspace_but_not_accounts(): void
    {
        $this->actingAs($this->admin());
        $this->get('/admin/organisations')->assertOk()->assertSee('Organisations');
        $this->get('/admin/makers')->assertOk()->assertSee('Makers');
        $this->get('/admin/teams')->assertOk()->assertSee('Teams');
        $this->get('/admin/users')->assertForbidden();
    }

    public function test_resources_cannot_open_an_application_of_the_wrong_audience(): void
    {
        $organisation = $this->application('organisations');
        $maker = $this->application('makers');
        $this->actingAs($this->admin());
        $this->get('/admin/organisations/'.$maker->id.'/edit')->assertNotFound();
        $this->get('/admin/makers/'.$organisation->id.'/edit')->assertNotFound();
        $this->assertSame([$organisation->id], OrganisationResource::getEloquentQuery()->pluck('id')->all());
        $this->assertSame([$maker->id], MakerResource::getEloquentQuery()->pluck('id')->all());
    }

    public function test_organisation_edits_preserve_submitted_answers(): void
    {
        $application = $this->application('organisations');
        $original = $application->answers;
        $this->actingAs($this->admin());
        Filament::setCurrentPanel(Filament::getPanel('admin'));

        Livewire::test(EditOrganisation::class, ['record' => $application->id])
            ->fillForm(['status' => 'selected', 'refined_brief' => 'A clear youth campaign.', 'skills_needed' => ['graphic-design']])
            ->call('save')
            ->assertHasNoFormErrors();

        $this->assertSame('selected', $application->refresh()->status);
        $this->assertSame('A clear youth campaign.', $application->refined_brief);
        $this->assertSame(['graphic-design'], $application->skills_needed);
        $this->assertSame($original, $application->answers);
    }

    public function test_original_and_youth_eligibility_confirmations_keep_distinct_admin_labels(): void
    {
        $this->actingAs($this->admin());

        foreach ([
            'brussels_eligibility' => 'Supports people in Brussels (original confirmation)',
            'youth_brussels_eligibility' => 'Works with young people in Brussels',
        ] as $key => $label) {
            $answers = [$key => '1'];
            $application = $this->application('organisations', ['answers' => $answers]);

            $this->withoutVite()->get('/admin/organisations/'.$application->id.'/edit')
                ->assertOk()->assertSee($label);

            $this->assertSame($answers, $application->fresh()->answers);
        }
    }

    public function test_review_action_always_belongs_to_the_signed_in_organiser(): void
    {
        $application = $this->application('organisations');
        $other = $this->admin();
        $application->reviews()->create(['user_id' => $other->id, 'rating' => 2, 'comment' => 'Other assessment']);
        $actor = $this->admin();
        $this->actingAs($actor);
        Filament::setCurrentPanel(Filament::getPanel('admin'));

        Livewire::test(EditOrganisation::class, ['record' => $application->id])
            ->callAction('review', data: ['rating' => 4, 'comment' => 'My assessment', 'user_id' => $other->id])
            ->assertHasNoActionErrors();

        $this->assertDatabaseHas('application_reviews', ['application_id' => $application->id, 'user_id' => $actor->id, 'rating' => 4]);
        $this->assertDatabaseHas('application_reviews', ['application_id' => $application->id, 'user_id' => $other->id, 'rating' => 2]);
    }

    public function test_team_form_saves_eligible_members_and_rejects_another_edition(): void
    {
        $organisation = $this->application('organisations', ['status' => 'selected']);
        $maker = $this->application('makers', ['status' => 'selected']);
        $oldMaker = $this->application('makers', ['status' => 'selected', 'edition_year' => 2025]);
        $this->actingAs($this->admin());
        Filament::setCurrentPanel(Filament::getPanel('admin'));

        Livewire::test(CreateTeam::class)
            ->fillForm(['name' => 'Youth team', 'edition_year' => 2027, 'organisation_application_id' => $organisation->id, 'status' => 'draft', 'maker_ids' => [$oldMaker->id]])
            ->call('create')->assertHasFormErrors();
        $this->assertDatabaseCount('teams', 0);

        Livewire::test(CreateTeam::class)
            ->fillForm(['name' => 'Youth team', 'edition_year' => 2027, 'organisation_application_id' => $organisation->id, 'status' => 'draft', 'maker_ids' => [$maker->id]])
            ->call('create')->assertHasNoFormErrors();
        $this->assertDatabaseHas('teams', ['name' => 'Youth team']);
        $this->assertDatabaseHas('application_team', ['application_id' => $maker->id]);
    }

    public function test_submitted_content_is_escaped_and_unsafe_links_are_not_clickable(): void
    {
        $application = $this->application('organisations', ['answers' => ['organisation_name' => 'Example', 'challenge' => '<script>alert(1)</script>', 'website' => 'javascript:alert(1)']]);
        $this->actingAs($this->admin())->get('/admin/organisations/'.$application->id.'/edit')
            ->assertOk()->assertSee('&lt;script&gt;alert(1)&lt;/script&gt;', false)
            ->assertDontSee('href="javascript:', false);
    }

    public function test_organisation_team_assignment_is_visible_and_filterable(): void
    {
        $organisation = $this->application('organisations', ['status' => 'selected']);
        app(Matchmaking::class)->save(new Team, ['name' => 'Youth team', 'edition_year' => 2027, 'organisation_application_id' => $organisation->id, 'status' => 'draft'], []);
        $this->actingAs($this->admin())->get('/admin/organisations')->assertOk()->assertSee('Youth team');
    }

    public function test_team_can_be_saved_twice_without_a_false_stale_edit_error(): void
    {
        $organisation = $this->application('organisations', ['status' => 'selected']);
        $team = app(Matchmaking::class)->save(new Team, ['name' => 'Youth team', 'edition_year' => 2027, 'organisation_application_id' => $organisation->id, 'status' => 'draft'], []);
        $this->actingAs($this->admin());
        Filament::setCurrentPanel(Filament::getPanel('admin'));

        Livewire::test(EditTeam::class, ['record' => $team->id])
            ->fillForm(['notes' => 'First save'])->call('save')->assertHasNoFormErrors()
            ->fillForm(['notes' => 'Second save'])->call('save')->assertHasNoFormErrors();
        $this->assertSame('Second save', $team->refresh()->notes);
    }

    public function test_an_assigned_organisation_cannot_be_rejected_without_removing_its_team(): void
    {
        $organisation = $this->application('organisations', ['status' => 'selected']);
        app(Matchmaking::class)->save(new Team, ['name' => 'Youth team', 'edition_year' => 2027, 'organisation_application_id' => $organisation->id, 'status' => 'draft'], []);
        $this->actingAs($this->admin());
        Filament::setCurrentPanel(Filament::getPanel('admin'));

        Livewire::test(EditOrganisation::class, ['record' => $organisation->id])
            ->fillForm(['status' => 'rejected'])->call('save')->assertHasFormErrors(['status']);
        $this->assertSame('selected', $organisation->refresh()->status);
    }

    public function test_password_resets_and_profile_use_the_same_strong_password_policy(): void
    {
        $this->assertTrue(Validator::make(['password' => 'password'], ['password' => Password::default()])->fails());
        $this->assertFalse(Validator::make(['password' => 'Unique-Admin-2027!'], ['password' => Password::default()])->fails());
    }

    public function test_deleting_a_team_releases_assignments_and_keeps_applications(): void
    {
        $organisation = $this->application('organisations', ['status' => 'selected']);
        $maker = $this->application('makers', ['status' => 'selected']);
        $team = app(Matchmaking::class)->save(new Team, ['name' => 'Youth team', 'edition_year' => 2027, 'organisation_application_id' => $organisation->id, 'status' => 'draft'], [$maker->id]);
        $this->actingAs($this->admin());
        Filament::setCurrentPanel(Filament::getPanel('admin'));

        Livewire::test(EditTeam::class, ['record' => $team->id])->callAction('delete')->assertHasNoActionErrors();
        $this->assertModelMissing($team);
        $this->assertModelExists($organisation);
        $this->assertModelExists($maker);
        $this->assertDatabaseCount('application_team', 0);
    }

    public function test_team_picker_shows_unassigned_candidates_before_a_filter_is_chosen(): void
    {
        $organisation = $this->application('organisations', ['status' => 'selected']);
        $maker = $this->application('makers', ['status' => 'selected']);
        $this->actingAs($this->admin());
        Filament::setCurrentPanel(Filament::getPanel('admin'));

        Livewire::test(CreateTeam::class)
            ->fillForm(['edition_year' => 2027, 'organisation_application_id' => $organisation->id])
            ->assertFormFieldExists('maker_ids', fn (Select $field): bool => array_key_exists($maker->id, $field->getOptions()));
    }

    public function test_candidate_filters_keep_current_members_and_exclude_other_team_assignments(): void
    {
        $organisation = $this->application('organisations', ['status' => 'selected']);
        $otherOrganisation = $this->application('organisations', ['status' => 'selected']);
        $current = $this->application('makers', ['status' => 'selected', 'main_skills' => ['graphic-design']]);
        $available = $this->application('makers', ['status' => 'selected', 'main_skills' => ['web-design']]);
        $assignedElsewhere = $this->application('makers', ['status' => 'selected', 'main_skills' => ['web-design']]);
        $team = app(Matchmaking::class)->save(new Team, ['name' => 'Youth team', 'edition_year' => 2027, 'organisation_application_id' => $organisation->id, 'status' => 'draft'], [$current->id]);
        app(Matchmaking::class)->save(new Team, ['name' => 'Other team', 'edition_year' => 2027, 'organisation_application_id' => $otherOrganisation->id, 'status' => 'draft'], [$assignedElsewhere->id]);
        $this->actingAs($this->admin());
        Filament::setCurrentPanel(Filament::getPanel('admin'));

        Livewire::test(EditTeam::class, ['record' => $team->id])
            ->fillForm(['candidate_skill' => 'web-design'])
            ->assertFormFieldExists('maker_ids', function (Select $field) use ($current, $available, $assignedElsewhere): bool {
                $options = $field->getOptions();

                return isset($options[$current->id], $options[$available->id]) && ! isset($options[$assignedElsewhere->id]);
            });
    }

    private function admin(): User
    {
        return User::factory()->create(['is_admin' => true, 'is_active' => true]);
    }

    private function application(string $audience, array $attributes = []): Application
    {
        return Application::create(array_replace([
            'audience' => $audience, 'edition_year' => 2027,
            'contact_name' => 'Example applicant', 'email' => fake()->unique()->safeEmail(),
            'answers' => ['organisation_name' => 'Example organisation', 'challenge' => 'Youth communication', 'main_skills' => 'Graphic design'],
        ], $attributes));
    }
}
