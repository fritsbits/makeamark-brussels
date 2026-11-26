<?php

namespace Tests\Concerns;

use Illuminate\Testing\TestResponse;

trait SubmitsApplicationForms
{
    protected function formMetadata(string $audience, ?TestResponse $page = null): array
    {
        $this->freezeTime();
        $page ??= $this->withoutVite()->get('/'.$audience)->assertOk();
        $this->withCookie(config('session.cookie'), session()->getId());
        $this->travel(3)->seconds();

        return [
            '_submission_token' => $page->viewData('submissionToken'),
            '_form_started' => $page->viewData('spamStartedToken'),
            'website_confirmation' => '',
            'cf-turnstile-response' => 'fixture-'.$audience,
        ];
    }

    protected function validApplication(string $audience): array
    {
        $answers = match ($audience) {
            'makers' => ['name' => 'José', 'phone' => '0470000000', 'motivation' => 'Help https://example.com', 'portfolio' => 'https://example.com', 'main_skills' => 'Design', 'date_confirmed' => '1', 'meeting_availability' => 'Evenings', 'full_commitment' => '1'],
            'organisations' => ['organisation_name' => 'Youth', 'contact_name' => 'José', 'phone' => '0470000000', 'youth_brussels_eligibility' => '1', 'mission' => 'Help youth', 'challenge' => 'Communication', 'organisation_languages' => ['French'], 'languages' => ['English'], 'assets_ready' => '1', 'planning_meeting' => '1', 'feedback_session' => '1', 'evening_presentation' => '1', 'website' => 'https://example.com'],
            'volunteers' => ['name' => 'José', 'phone' => '0470000000', 'roles' => ['photographer'], 'portfolio' => 'https://example.com', 'date_confirmed' => '1', 'day_parts' => ['daytime']],
        };

        return [...$this->formMetadata($audience), ...$answers, 'email' => 'person@example.com'];
    }
}
