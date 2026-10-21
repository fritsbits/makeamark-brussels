<?php

namespace App\Http\Controllers;

use App\Content\Homepage;
use App\Mail\ApplicationReceived;
use App\Models\Application;
use App\Support\ApplicationForm;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class ApplicationSubmissionController extends Controller
{
    public function __invoke(Request $request, string $audience, Homepage $homepage): RedirectResponse
    {
        abort_unless(in_array($audience, ['makers', 'organisations'], true), 404);

        $edition = $homepage->data()['edition'];

        if ($edition['status'] !== 'open') {
            return ApplicationForm::recover($request, $audience, $edition['status'] === 'preparing'
                ? 'Applications aren’t open yet, so your application has not been sent. Contact the organising team if you need help.'
                : 'Applications are now closed. Your application has not been sent. Contact the organising team if you need help.');
        }

        $submissionKey = ApplicationForm::submissionKey($request, $audience, $edition['year']);

        if ($submissionKey === null) {
            return ApplicationForm::recover($request, $audience, 'Your form has expired. Please review your answers and send your application again.');
        }

        $common = [
            'email' => ['required', 'email:rfc', 'max:255'],
        ];

        $rules = $audience === 'makers'
            ? [
                ...$common,
                'name' => ['required', 'string', 'max:255'],
                'motivation' => ['required', 'string', 'max:5000'],
                'referral' => ['nullable', 'string', 'max:1000'],
                'dutch' => ['nullable', Rule::in(['1', '2', '3', '4', '5'])],
                'english' => ['nullable', Rule::in(['1', '2', '3', '4', '5'])],
                'french' => ['nullable', Rule::in(['1', '2', '3', '4', '5'])],
                'other_languages' => ['nullable', 'string', 'max:1000'],
                'portfolio' => ['required', 'url', 'max:2000'],
                'social_media' => ['nullable', 'string', 'max:2000'],
                'main_skills' => ['required', 'string', 'max:3000'],
                'backup_skills' => ['nullable', 'string', 'max:3000'],
                'other_skills' => ['nullable', 'string', 'max:3000'],
                'team_preferences' => ['nullable', 'string', 'max:2000'],
                'themes' => ['sometimes', 'array', 'max:10'],
                'themes.*' => ['string', 'distinct', Rule::in($this->themes())],
                'date_confirmed' => ['accepted'],
                'meeting_availability' => ['required', 'string', 'max:2000'],
                'full_commitment' => ['accepted'],
            ]
            : [
                ...$common,
                'organisation_name' => ['required', 'string', 'max:255'],
                'youth_brussels_eligibility' => ['accepted'],
                'mission' => ['required', 'string', 'max:5000'],
                'social_media' => ['nullable', 'string', 'max:2000'],
                'website' => ['nullable', 'url', 'max:2000'],
                'challenge' => ['required', 'string', 'max:5000'],
                'contact_name' => ['required', 'string', 'max:255'],
                'phone' => ['required', 'string', 'max:80'],
                'languages' => ['required', 'array', 'min:1', 'max:3'],
                'languages.*' => ['string', 'distinct', Rule::in(['English', 'Dutch', 'French'])],
                'assets_ready' => ['accepted'],
                'planning_meeting' => ['accepted'],
                'feedback_session' => ['accepted'],
                'evening_presentation' => ['accepted'],
            ];

        $applicationUrl = route('audience.show', $audience).'#application';
        $validator = Validator::make($request->all(), $rules, [
            'youth_brussels_eligibility.accepted' => 'Please confirm that your organisation works with young people in Brussels.',
        ]);

        if ($validator->fails()) {
            if (! $request->expectsJson()) {
                return redirect($applicationUrl)->withErrors($validator)->withInput(ApplicationForm::normalize($request->input(), $audience));
            }

            throw (new ValidationException($validator))->redirectTo($applicationUrl);
        }

        $answers = $validator->validated();
        $name = $audience === 'makers' ? $answers['name'] : $answers['contact_name'];

        $attributes = [
            'audience' => $audience,
            'edition_year' => $edition['year'],
            'contact_name' => $name,
            'email' => $answers['email'],
            'answers' => $answers,
        ];

        // The unique database key also resolves concurrent retries, unlike a disabled browser button.
        $application = Application::firstOrCreate(['submission_key' => $submissionKey], $attributes);

        $receiptSent = $application->receipt_sent_at !== null;

        if ($application->wasRecentlyCreated && config('mail.default') === 'resend' && filled(config('services.resend.key')) && filled(config('mail.from.address')) && config('mail.from.address') !== 'hello@example.com') {
            try {
                Mail::mailer('resend')->to($application->email)->send(new ApplicationReceived($name, $audience, $edition['year']));
                $receiptSent = true;
                $application->forceFill(['receipt_sent_at' => now()])->save();
            } catch (\Throwable) {
                Log::warning('Application email delivery failed.', ['application_id' => $application->id]);
            }
        }

        // Retain each audience's confirmation until its own page consumes it, including other-tab navigation.
        $request->session()->put('application_confirmations.'.$audience, [
            'email_sent' => $receiptSent,
            'expires_at' => time() + 600,
        ]);

        return redirect($applicationUrl);
    }

    private function themes(): array
    {
        return [
            'Poverty reduction and social security', 'Culture and community building',
            'Health and well-being', 'Housing and living conditions', 'Local economy and employment',
            'Environment and sustainability', 'Education and youth development',
            'Social inclusion and diversity', 'Sports and recreation', 'Refugee and migrant support',
        ];
    }
}
