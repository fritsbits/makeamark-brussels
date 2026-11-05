<?php

namespace Tests\Feature;

use App\Filament\Resources\MakerResource\Pages\ListMakers;
use App\Filament\Resources\OrganisationResource\Pages\ListOrganisations;
use App\Filament\Resources\TeamResource\Pages\CreateTeam;
use App\Models\Application;
use App\Models\User;
use App\Support\Themes;
use Filament\Facades\Filament;
use Filament\Forms\Components\Select;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Livewire\Livewire;
use Tests\TestCase;

class SharedThemesTest extends TestCase
{
    use RefreshDatabase;

    public function test_the_catalogue_holds_the_ten_themes_and_keeps_only_known_ones_once(): void
    {
        $this->assertCount(10, Themes::all());
        $this->assertSame(['Health and well-being', 'Sports and recreation'], Themes::normalise(['Sports and recreation', 'Unknown', 'Health and well-being', 'Sports and recreation', ['nested']]));
        $this->assertSame([], Themes::normalise('Health and well-being'));
    }

    public function test_both_forms_offer_the_same_ten_themes(): void
    {
        foreach (['makers', 'organisations'] as $audience) {
            $page = $this->withoutVite()->get('/'.$audience)->assertOk();
            foreach (Themes::all() as $theme) {
                $page->assertSee($theme);
            }
        }
        $this->withoutVite()->get('/organisations')->assertSee('Within your work with young people, which themes does your work address?');
    }

    public function test_an_organisation_can_submit_themes_and_the_column_mirrors_the_answers(): void
    {
        $this->post('/organisations/apply', $this->organisationAnswers(['themes' => ['Health and well-being', 'Sports and recreation']]))->assertSessionHasNoErrors();

        $application = Application::sole();
        $this->assertSame(['Health and well-being', 'Sports and recreation'], $application->themes);
        $this->assertSame(['Health and well-being', 'Sports and recreation'], $application->answers['themes']);
    }

    public function test_organisation_themes_are_optional(): void
    {
        $this->post('/organisations/apply', $this->organisationAnswers())->assertSessionHasNoErrors();

        $this->assertSame([], Application::sole()->themes);
    }

    public function test_organisation_themes_are_validated_like_the_maker_themes(): void
    {
        foreach ([
            'scalar' => [['themes' => 'Health and well-being'], 'themes'],
            'nested' => [['themes' => [['nested']]], 'themes.0'],
            'unknown' => [['themes' => ['Made up']], 'themes.0'],
            'duplicate' => [['themes' => ['Sports and recreation', 'Sports and recreation']], 'themes.0'],
            'too many' => [['themes' => array_fill(0, 11, 'Sports and recreation')], 'themes'],
        ] as $label => [$changes, $error]) {
            $this->post('/organisations/apply', $this->organisationAnswers($changes))->assertSessionHasErrors($error);
            $this->assertDatabaseCount('applications', 0);
        }
    }

    public function test_the_migration_backfills_existing_rows_and_keeps_answers_as_submitted(): void
    {
        Artisan::call('migrate:rollback', ['--step' => 2]);
        $this->assertFalse(Schema::hasColumn('applications', 'themes'));
        $row = ['audience' => 'makers', 'edition_year' => 2027, 'contact_name' => 'A', 'email' => 'a@example.com', 'status' => 'applied', 'created_at' => now(), 'updated_at' => now()];
        DB::table('applications')->insert([
            $row + ['answers' => json_encode(['themes' => ['Sports and recreation', 'Not a theme', 'Health and well-being']])],
            ['email' => 'b@example.com'] + $row + ['answers' => json_encode(['name' => 'No themes'])],
        ]);

        Artisan::call('migrate');

        $this->assertSame(['Health and well-being', 'Sports and recreation'], Application::where('email', 'a@example.com')->sole()->themes);
        $this->assertSame([], Application::where('email', 'b@example.com')->sole()->themes);
        $this->assertSame(['Sports and recreation', 'Not a theme', 'Health and well-being'], Application::where('email', 'a@example.com')->sole()->answers['themes']);
    }

    public function test_the_rename_migration_rewrites_the_education_theme_in_the_column_and_the_answers(): void
    {
        $this->assertContains('Education and learning', Themes::all());
        $this->assertNotContains('Education and youth development', Themes::all());
        Artisan::call('migrate:rollback', ['--step' => 1]);
        $row = ['edition_year' => 2027, 'contact_name' => 'A', 'status' => 'applied', 'created_at' => now(), 'updated_at' => now()];
        $themes = ['Health and well-being', 'Education and youth development'];
        DB::table('applications')->insert([
            $row + ['audience' => 'makers', 'email' => 'm@example.com', 'themes' => json_encode($themes), 'answers' => json_encode(['name' => 'M', 'themes' => $themes])],
            $row + ['audience' => 'organisations', 'email' => 'o@example.com', 'themes' => json_encode($themes), 'answers' => json_encode(['organisation_name' => 'O', 'themes' => $themes])],
            $row + ['audience' => 'organisations', 'email' => 'n@example.com', 'themes' => json_encode(['Sports and recreation']), 'answers' => json_encode(['organisation_name' => 'N'])],
        ]);

        Artisan::call('migrate');

        foreach (['m@example.com', 'o@example.com'] as $email) {
            $application = Application::where('email', $email)->sole();
            $this->assertSame(['Health and well-being', 'Education and learning'], $application->themes);
            $this->assertSame(['Health and well-being', 'Education and learning'], $application->answers['themes']);
        }
        $untouched = Application::where('email', 'n@example.com')->sole();
        $this->assertSame(['Sports and recreation'], $untouched->themes);
        $this->assertArrayNotHasKey('themes', $untouched->answers);
    }

    public function test_the_admin_lists_filter_by_theme(): void
    {
        $education = $this->application('organisations', ['themes' => ['Education and learning']]);
        $sport = $this->application('organisations', ['themes' => ['Sports and recreation']]);
        $makerSport = $this->application('makers', ['themes' => ['Sports and recreation']]);
        $makerNone = $this->application('makers');
        $this->actingAs($this->admin());
        Filament::setCurrentPanel(Filament::getPanel('admin'));

        Livewire::test(ListOrganisations::class)
            ->filterTable('theme', 'Sports and recreation')
            ->assertCanSeeTableRecords([$sport])->assertCanNotSeeTableRecords([$education]);
        Livewire::test(ListMakers::class)
            ->filterTable('theme', 'Sports and recreation')
            ->assertCanSeeTableRecords([$makerSport])->assertCanNotSeeTableRecords([$makerNone]);
    }

    public function test_the_team_editor_shows_which_themes_the_organisation_and_each_maker_share(): void
    {
        $organisation = $this->application('organisations', ['status' => 'selected', 'themes' => ['Health and well-being', 'Sports and recreation']]);
        $overlap = $this->application('makers', ['status' => 'selected', 'themes' => ['Sports and recreation', 'Housing and living conditions']]);
        $apart = $this->application('makers', ['status' => 'selected', 'themes' => ['Housing and living conditions']]);
        $this->actingAs($this->admin());
        Filament::setCurrentPanel(Filament::getPanel('admin'));

        Livewire::test(CreateTeam::class)
            ->fillForm(['edition_year' => 2027, 'organisation_application_id' => $organisation->id, 'candidate_language' => []])
            ->assertSee('Health and well-being')
            ->assertFormFieldExists('maker_ids', function (Select $field) use ($overlap, $apart): bool {
                $options = $field->getOptions();

                return str_contains($options[$overlap->id], 'shared themes: Sports and recreation')
                    && ! str_contains($options[$apart->id], 'shared themes');
            })
            ->fillForm(['maker_ids' => [$overlap->id]])
            ->assertSee('Themes shared with the organisation')
            ->assertSeeInOrder(['Themes shared with the organisation', 'Sports and recreation']);
    }

    public function test_the_print_briefing_reads_the_themes_column(): void
    {
        $organisation = $this->application('organisations', ['themes' => ['Sports and recreation']]);
        $this->actingAs($this->admin());

        $this->withoutVite()->get(route('filament.admin.print.organisation', $organisation))->assertOk()->assertSee('Sports and recreation');
    }

    private function application(string $audience, array $attributes = []): Application
    {
        return Application::create(array_replace([
            'audience' => $audience, 'edition_year' => 2027,
            'contact_name' => fake()->name(), 'email' => fake()->unique()->safeEmail(),
            'answers' => ['organisation_name' => fake()->company()],
        ], $attributes));
    }

    private function organisationAnswers(array $changes = []): array
    {
        $page = $this->withoutVite()->get('/organisations')->assertOk();
        $this->withCookie(config('session.cookie'), session()->getId());

        return array_replace([
            '_submission_token' => $page->viewData('submissionToken'),
            'organisation_name' => 'Test Organisation', 'email' => 'organisation@example.com',
            'youth_brussels_eligibility' => '1', 'mission' => 'Support local youth.',
            'challenge' => 'Improve our communication.', 'contact_name' => 'Test Contact',
            'phone' => '0123456789', 'organisation_languages' => ['French'], 'languages' => ['English'],
            'assets_ready' => '1', 'planning_meeting' => '1', 'feedback_session' => '1', 'evening_presentation' => '1',
        ], $changes);
    }

    private function admin(): User
    {
        return User::factory()->create(['is_admin' => true, 'is_active' => true]);
    }
}
