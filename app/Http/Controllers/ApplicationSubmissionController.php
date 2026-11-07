<?php

namespace App\Http\Controllers;

use App\Content\Homepage;
use App\Mail\ApplicationReceived;
use App\Models\Application;
use App\Support\ApplicationForm;
use App\Support\Audiences;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class ApplicationSubmissionController extends Controller
{
    public function __invoke(Request $request, string $audience, Homepage $homepage): RedirectResponse
    {
        $definition = Audiences::find($audience);
        $edition = $homepage->data()['edition'];

        if (! $definition->isOpen($edition)) {
            return ApplicationForm::recover($request, $audience, $definition->closedMessage($edition));
        }

        $submissionKey = ApplicationForm::submissionKey($request, $audience, $edition['year']);

        if ($submissionKey === null) {
            return ApplicationForm::recover($request, $audience, 'Your form has expired. Please review your answers and send your application again.');
        }

        $applicationUrl = route('audience.show', $audience).'#application';
        $validator = Validator::make($request->all(), $definition->rules($request->all()), $definition->messages);

        if ($validator->fails()) {
            if (! $request->expectsJson()) {
                return redirect($applicationUrl)->withErrors($validator)->withInput(ApplicationForm::normalize($request->input(), $audience));
            }

            throw (new ValidationException($validator))->redirectTo($applicationUrl);
        }

        $answers = $validator->validated();
        $name = $answers[$definition->nameField];

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
            'application_id' => $application->id,
            'expires_at' => time() + 600,
        ]);

        return redirect($applicationUrl);
    }
}
