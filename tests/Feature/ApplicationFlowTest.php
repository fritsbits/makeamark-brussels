<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ApplicationFlowTest extends TestCase
{
    use RefreshDatabase;

    public function test_invalid_applications_return_to_the_inline_form_with_answers_and_errors(): void
    {
        foreach (['makers', 'organisations'] as $audience) {
            $input = ['email' => 'invalid'];

            $input['_submission_token'] = $this->withoutVite()->get('/'.$audience)->viewData('submissionToken');
            $this->withCookie(config('session.cookie'), session()->getId());

            $this->from('/'.$audience)->post('/'.$audience.'/apply', $input)
                ->assertRedirect('/'.$audience.'#application')
                ->assertSessionHasErrors('email')
                ->assertSessionHasInput('email', 'invalid');

            $response = $this->withCookie(config('session.cookie'), session()->getId())
                ->withoutVite()->get('/'.$audience)->assertOk()
                ->assertSee('Some details need your attention.')
                ->assertSee('value="invalid"', false);

            if ($audience === 'makers') {
                $response->assertSee('href="#email"', false)
                    ->assertSee('id="email"', false);
            }
        }

        $this->assertDatabaseCount('applications', 0);
    }

    public function test_successful_applications_are_saved_and_confirmed_on_the_information_page(): void
    {
        config(['mail.default' => 'log']);

        $applications = [
            'makers' => [
                'name' => 'Test Maker', 'email' => 'maker@example.com',
                'motivation' => 'Contribute to a local project.', 'portfolio' => 'https://example.com/work',
                'main_skills' => 'Design', 'date_confirmed' => '1',
                'meeting_availability' => 'Weekday evenings', 'full_commitment' => '1',
            ],
            'organisations' => [
                'organisation_name' => 'Test Organisation', 'email' => 'organisation@example.com',
                'youth_brussels_eligibility' => '1', 'mission' => 'Support local youth.',
                'challenge' => 'Improve our communication.', 'contact_name' => 'Test Contact',
                'phone' => '0123456789', 'languages' => ['English'], 'assets_ready' => '1',
                'planning_meeting' => '1', 'feedback_session' => '1', 'evening_presentation' => '1',
            ],
        ];

        foreach ($applications as $audience => $answers) {
            $answers['_submission_token'] = $this->withoutVite()->get('/'.$audience)->viewData('submissionToken');
            $this->withCookie(config('session.cookie'), session()->getId());

            $this->post('/'.$audience.'/apply', $answers)
                ->assertRedirect('/'.$audience.'#application')
                ->assertSessionHasNoErrors();

            $this->assertDatabaseHas('applications', ['audience' => $audience, 'email' => $answers['email']]);
            $this->withoutVite()->get('/'.$audience)->assertOk()
                ->assertSee('Application received')
                ->assertDontSee('class="application-form"', false);
            $this->get('/'.$audience)->assertSee('class="application-form"', false);
        }
    }
}
