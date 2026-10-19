<?php

namespace Tests\Feature;

use App\Models\Application;
use App\Models\ApplicationReview;
use App\Models\Team;
use App\Models\User;
use App\Services\Matchmaking;
use App\Support\Skills;
use Filament\Panel;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\ValidationException;
use Tests\TestCase;

class AdminDomainTest extends TestCase
{
    use RefreshDatabase;

    public function test_skill_variants_are_canonical_and_unknown_answers_remain_available(): void
    {
        $this->assertCount(19, Skills::options());
        $this->assertSame(['art-direction', 'content-development-copywriting', 'front-end-development', 'back-end-development', 'ux-design'], Skills::normalise('Art director, Content development/copywriting; Front end web development; Back end web development; UX; unlisted specialism'));
        $answers = ['main_skills' => 'Art director, photography', 'backup_skills' => 'UX', 'other_skills' => 'Unlisted specialism'];
        $maker = $this->application('makers', ['answers' => $answers]);
        $this->assertSame(['art-direction', 'photography'], $maker->fresh()->main_skills);
        $this->assertSame(['ux-design'], $maker->fresh()->backup_skills);
        $this->assertSame($answers, $maker->fresh()->answers);
        $this->assertSame([], $maker->skills_needed);
        $this->assertSame([], $this->application('makers', ['answers' => $answers, 'main_skills' => []])->main_skills);
    }

    public function test_only_active_administrators_can_enter_the_panel_and_use_policies(): void
    {
        $panel = Panel::make()->id('admin');
        $member = User::factory()->create();
        $admin = User::factory()->create(['is_admin' => true]);
        $super = User::factory()->create(['is_super_admin' => true]);
        $disabled = User::factory()->create(['is_admin' => true, 'is_active' => false]);
        $this->assertFalse($member->canAccessPanel($panel));
        $this->assertFalse($disabled->canAccessPanel($panel));
        $this->assertTrue($admin->canAccessPanel($panel));
        $this->assertTrue($super->canAccessPanel($panel));
        $this->assertFalse(Gate::forUser($member)->allows('viewAny', Application::class));
        $this->assertFalse(Gate::forUser($disabled)->allows('create', Team::class));
        $this->assertTrue(Gate::forUser($admin)->allows('viewAny', Application::class));
        $this->assertFalse(Gate::forUser($admin)->allows('viewAny', User::class));
        $this->assertTrue(Gate::forUser($super)->allows('viewAny', User::class));
        $this->assertFalse(Gate::forUser($super)->allows('delete', $super));
    }

    public function test_reviews_are_independent_owned_and_average_excludes_missing_ratings(): void
    {
        $organisation = $this->application();
        $alice = User::factory()->create(['is_admin' => true]);
        $bob = User::factory()->create(['is_admin' => true]);
        $review = $organisation->reviews()->create(['user_id' => $alice->id, 'rating' => 5, 'comment' => 'Clear brief']);
        $organisation->reviews()->create(['user_id' => $bob->id, 'rating' => 2]);
        $this->assertEquals(3.5, $organisation->fresh()->average_rating);
        $this->assertNull($this->application()->average_rating);
        $this->assertSame($alice->id, $review->user->id);
        $this->assertTrue(Gate::forUser($alice)->allows('update', $review));
        $this->assertFalse(Gate::forUser($bob)->allows('update', $review));
        $this->expectException(QueryException::class);
        $organisation->reviews()->create(['user_id' => $alice->id, 'rating' => 1]);
    }

    public function test_reviews_reject_out_of_range_ratings_and_maker_applications(): void
    {
        $admin = User::factory()->create(['is_admin' => true]);
        foreach ([['organisations', 0], ['organisations', 6], ['makers', 3]] as [$audience, $rating]) {
            try {
                ApplicationReview::create(['application_id' => $this->application($audience)->id, 'user_id' => $admin->id, 'rating' => $rating]);
                $this->fail('Invalid review was saved.');
            } catch (ValidationException) {
                $this->assertDatabaseCount('application_reviews', 0);
            }
        }
    }

    public function test_matchmaking_persists_and_updates_the_team_and_members_together(): void
    {
        $organisation = $this->application('organisations', ['answers' => ['organisation_name' => 'Community project']]);
        $maker = $this->application('makers');
        $team = app(Matchmaking::class)->save(new Team, $this->teamAttributes($organisation), [$maker->id]);
        $this->assertSame('Community project', $team->organisation->display_name);
        $this->assertSame($team->id, $organisation->organisationTeam->id);
        $this->assertSame([$organisation->id], Application::has('organisationTeam')->pluck('id')->all());
        $this->assertNull($maker->organisationTeam);
        $this->assertSame($team->id, $maker->teams()->first()->id);
        $this->assertSame(1, $team->lock_version);
        $updated = app(Matchmaking::class)->save($team, ['name' => 'Updated', 'lock_version' => 1], []);
        $this->assertSame(2, $updated->lock_version);
        $this->assertSame('Updated', $updated->name);
        $this->assertCount(0, $updated->makers);
    }

    public function test_matchmaking_rejects_wrong_audience_edition_status_and_duplicate_ids_without_creating_team(): void
    {
        $organisation = $this->application();
        $maker = $this->application('makers');
        $invalid = [
            [$this->teamAttributes($maker), [$maker->id], 'organisation_application_id'],
            [$this->teamAttributes($organisation), [$organisation->id], 'maker_ids'],
            [$this->teamAttributes($organisation), [$this->application('makers', ['edition_year' => 2028])->id], 'maker_ids'],
            [$this->teamAttributes($organisation), [$this->application('makers', ['status' => 'applied'])->id], 'maker_ids'],
            [$this->teamAttributes($this->application('organisations', ['status' => 'applied'])), [], 'organisation_application_id'],
            [$this->teamAttributes($organisation), [$maker->id, $maker->id], 'maker_ids'],
            [$this->teamAttributes($organisation, ['edition_year' => 2028]), [], 'organisation_application_id'],
        ];
        foreach ($invalid as [$attributes, $makers, $key]) {
            try {
                app(Matchmaking::class)->save(new Team, $attributes, $makers);
                $this->fail('Invalid match was saved.');
            } catch (ValidationException $exception) {
                $this->assertArrayHasKey($key, $exception->errors());
                $this->assertDatabaseCount('teams', 0);
            }
        }
    }

    public function test_assigned_makers_cannot_be_moved_and_failed_updates_leave_existing_team_untouched(): void
    {
        $maker = $this->application('makers');
        $first = app(Matchmaking::class)->save(new Team, $this->teamAttributes($this->application()), [$maker->id]);
        $other = app(Matchmaking::class)->save(new Team, $this->teamAttributes($this->application()), []);
        try {
            app(Matchmaking::class)->save($other, ['name' => 'Should roll back', 'lock_version' => 1], [$maker->id]);
            $this->fail('Assigned maker was moved.');
        } catch (ValidationException $exception) {
            $this->assertArrayHasKey('maker_ids', $exception->errors());
        }
        $this->assertSame('A team', $other->fresh()->name);
        $this->assertSame(1, $other->fresh()->lock_version);
        $this->assertSame($first->id, $maker->teams()->first()->id);
        $this->assertCount(0, $other->fresh()->makers);
    }

    public function test_stale_team_updates_do_not_overwrite_a_newer_save(): void
    {
        $team = app(Matchmaking::class)->save(new Team, $this->teamAttributes($this->application()), []);
        app(Matchmaking::class)->save($team, ['name' => 'Newer', 'lock_version' => 1], []);
        try {
            app(Matchmaking::class)->save($team, ['name' => 'Stale', 'lock_version' => 1], []);
            $this->fail('Stale edit was saved.');
        } catch (ValidationException $exception) {
            $this->assertArrayHasKey('lock_version', $exception->errors());
        }
        $this->assertSame('Newer', $team->fresh()->name);
        $this->assertSame(2, $team->fresh()->lock_version);
    }

    public function test_database_failure_after_team_write_rolls_back_team_and_memberships(): void
    {
        $maker = $this->application('makers');
        $team = app(Matchmaking::class)->save(new Team, $this->teamAttributes($this->application()), [$maker->id]);
        $replacement = $this->application('makers');
        // SQLite test database: force the pivot insert to fail after the team was updated and the previous maker detached.
        DB::unprepared("CREATE TRIGGER fail_membership BEFORE INSERT ON application_team BEGIN SELECT RAISE(ABORT, 'Simulated storage failure'); END");
        try {
            app(Matchmaking::class)->save($team, ['name' => 'Must roll back', 'lock_version' => 1], [$replacement->id]);
            $this->fail('Storage failure was ignored.');
        } catch (QueryException|ValidationException) {
            $this->assertSame('A team', $team->fresh()->name);
            $this->assertSame(1, $team->fresh()->lock_version);
            $this->assertSame([$maker->id], $team->fresh()->makers->modelKeys());
        } finally {
            DB::unprepared('DROP TRIGGER fail_membership');
        }
    }

    public function test_organisation_cannot_have_multiple_teams_and_invalid_fields_do_not_persist(): void
    {
        $organisation = $this->application();
        app(Matchmaking::class)->save(new Team, $this->teamAttributes($organisation), []);
        foreach ([[$this->teamAttributes($organisation), 'organisation_application_id'], [$this->teamAttributes($this->application(), ['status' => 'invented']), 'status']] as [$attributes, $key]) {
            try {
                app(Matchmaking::class)->save(new Team, $attributes, []);
                $this->fail('Invalid team was saved.');
            } catch (ValidationException $exception) {
                $this->assertArrayHasKey($key, $exception->errors());
                $this->assertDatabaseCount('teams', 1);
            }
        }
    }

    public function test_assigned_application_cannot_be_rejected_or_moved_to_another_edition(): void
    {
        $organisation = $this->application();
        $maker = $this->application('makers');
        app(Matchmaking::class)->save(new Team, $this->teamAttributes($organisation), [$maker->id]);
        foreach ([[$maker, ['status' => 'rejected']], [$organisation, ['edition_year' => 2028]]] as [$application, $attributes]) {
            try {
                $application->update($attributes);
                $this->fail('Assigned application was made ineligible.');
            } catch (ValidationException) {
                $this->assertSame('selected', $application->fresh()->status);
                $this->assertSame(2027, $application->fresh()->edition_year);
            }
        }
    }

    public function test_last_usable_super_admin_cannot_be_deleted_or_disabled(): void
    {
        $super = User::factory()->create(['is_super_admin' => true]);
        User::factory()->create(['is_super_admin' => true, 'is_active' => false]);
        foreach (['disable', 'delete'] as $operation) {
            try {
                $operation === 'disable' ? $super->update(['is_active' => false]) : $super->delete();
                $this->fail('Last usable super admin was removed.');
            } catch (ValidationException) {
                $this->assertTrue($super->fresh()->is_active);
            }
        }
    }

    private function application(string $audience = 'organisations', array $attributes = []): Application
    {
        return Application::create(array_replace(['audience' => $audience, 'edition_year' => 2027, 'contact_name' => 'Applicant', 'email' => 'applicant@example.com', 'answers' => [], 'status' => 'selected'], $attributes));
    }

    private function teamAttributes(Application $organisation, array $attributes = []): array
    {
        return array_replace(['name' => 'A team', 'edition_year' => 2027, 'organisation_application_id' => $organisation->id, 'status' => 'draft'], $attributes);
    }
}
