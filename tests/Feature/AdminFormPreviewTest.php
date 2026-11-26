<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminFormPreviewTest extends TestCase
{
    use RefreshDatabase, \Tests\Concerns\SubmitsApplicationForms;

    protected function setUp(): void
    {
        parent::setUp();
        $this->withEditionStatus('preparing');
        config(['mail.default' => 'log']);
    }

    public function test_guests_and_non_admins_see_closed_forms(): void
    {
        foreach ([null, User::factory()->create(), User::factory()->create(['is_admin' => true, 'is_active' => false])] as $user) {
            if ($user) {
                $this->actingAs($user);
            }

            foreach (['makers', 'organisations'] as $audience) {
                $this->withoutVite()->get('/'.$audience)->assertOk()
                    ->assertSee('Applications open soon')
                    ->assertDontSee('class="application-form"', false)
                    ->assertDontSee('Admin preview');
            }
        }
    }

    public function test_admins_see_open_forms_with_a_preview_notice_that_is_never_cached(): void
    {
        $this->actingAs(User::factory()->create(['is_admin' => true]));

        foreach (['makers', 'organisations'] as $audience) {
            $response = $this->withoutVite()->get('/'.$audience)->assertOk()
                ->assertSee('Admin preview')
                ->assertSee('class="application-form"', false)
                ->assertSee('Start your application');
            $this->assertStringContainsString('no-store', $response->headers->get('Cache-Control'));
        }
    }

    public function test_open_forms_show_no_preview_notice_to_admins(): void
    {
        $this->withEditionStatus('open');
        $this->actingAs(User::factory()->create(['is_admin' => true]));

        $response = $this->withoutVite()->get('/makers')->assertOk()
            ->assertSee('class="application-form"', false)
            ->assertDontSee('Admin preview');
        $this->assertStringNotContainsString('no-store', $response->headers->get('Cache-Control'));
    }

    public function test_admins_can_send_a_preview_application(): void
    {
        $this->actingAs(User::factory()->create(['is_super_admin' => true]));

        $this->post('/makers/apply', $this->answers())
            ->assertRedirect('/makers#application')
            ->assertSessionHasNoErrors();

        $this->assertDatabaseHas('applications', ['audience' => 'makers', 'email' => 'maker@example.com']);
        $this->withoutVite()->get('/makers')->assertSee('Application received');
    }

    public function test_guests_still_cannot_send_while_closed(): void
    {
        $answers = $this->answers();
        auth()->logout();

        $this->post('/makers/apply', $answers)
            ->assertSessionHasErrors('submission');
        $this->assertDatabaseCount('applications', 0);
    }

    private function answers(): array
    {
        $this->actingAs(User::factory()->create(['is_admin' => true]));
        $metadata = $this->formMetadata('makers');
        $this->withCookie(config('session.cookie'), session()->getId());

        return [
            ...$metadata,
            'name' => 'Test Maker', 'email' => 'maker@example.com',
            'motivation' => 'Contribute to a local project.', 'portfolio' => 'https://example.com/work',
            'phone' => '0470 12 34 56', 'main_skills' => 'Design', 'date_confirmed' => '1',
            'meeting_availability' => 'Weekday evenings', 'full_commitment' => '1',
        ];
    }
}
