<?php

namespace Tests\Feature;

use App\Models\Application;
use App\Models\ApplicationReview;
use App\Models\Team;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class AdminDemoSeedTest extends TestCase
{
    use RefreshDatabase;

    public function test_demo_data_covers_review_selection_and_matching_without_creating_login_accounts(): void
    {
        $this->app->detectEnvironment(fn () => 'local');
        Mail::fake();
        $this->assertSame(0, Artisan::call('admin:seed-demo'));

        $this->assertSame(6, Application::where('audience', 'organisations')->count());
        $this->assertSame(18, Application::where('audience', 'makers')->count());
        $this->assertDatabaseCount('teams', 2);
        $this->assertDatabaseCount('application_reviews', 12);
        $this->assertTrue(User::query()->get()->every(fn (User $user): bool => ! $user->is_active && ! $user->is_admin && ! $user->is_super_admin));
        $this->assertGreaterThan(0, Application::where('audience', 'organisations')->whereDoesntHave('reviews')->count());
        $this->assertGreaterThan(0, Application::where('audience', 'makers')->whereIn('status', ['selected', 'confirmed'])->doesntHave('teams')->count());
        foreach (Team::with(['organisation', 'makers'])->get() as $team) {
            $this->assertSame($team->edition_year, $team->organisation->edition_year);
            $this->assertTrue($team->makers->every(fn (Application $maker): bool => $maker->audience === 'makers' && $maker->edition_year === $team->edition_year));
        }
        Mail::assertNothingSent();
    }

    public function test_reseeding_preserves_existing_data_demo_edits_and_credentials(): void
    {
        $this->app->detectEnvironment(fn () => 'local');
        $real = Application::create(['audience' => 'makers', 'edition_year' => 2027, 'contact_name' => 'Existing person', 'email' => 'existing@example.org', 'answers' => ['motivation' => 'Original application']]);
        $admin = User::factory()->create(['is_admin' => true, 'is_super_admin' => true]);
        $hash = $admin->password;
        $this->assertSame(0, Artisan::call('admin:seed-demo'));
        $organisation = Application::where('audience', 'organisations')->where('status', 'selected')->firstOrFail();
        $organisation->update(['refined_brief' => 'My test briefing']);
        $team = Team::firstOrFail();
        $team->update(['notes' => 'My matching notes']);
        $review = ApplicationReview::firstOrFail();
        $review->update(['comment' => 'My edited demo assessment']);

        $this->assertSame(0, Artisan::call('admin:seed-demo'));
        $this->assertDatabaseCount('applications', 25);
        $this->assertDatabaseCount('teams', 2);
        $this->assertDatabaseCount('application_reviews', 12);
        $this->assertSame('My test briefing', $organisation->refresh()->refined_brief);
        $this->assertSame('My matching notes', $team->refresh()->notes);
        $this->assertSame('My edited demo assessment', $review->refresh()->comment);
        $this->assertSame(['motivation' => 'Original application'], $real->refresh()->answers);
        $this->assertSame($hash, $admin->refresh()->password);
    }

    public function test_production_refuses_demo_data(): void
    {
        $this->app->detectEnvironment(fn () => 'production');
        $this->assertSame(1, Artisan::call('admin:seed-demo'));
        $this->assertDatabaseCount('applications', 0);
        $this->assertDatabaseCount('users', 0);
        $this->assertDatabaseCount('teams', 0);
    }
}
