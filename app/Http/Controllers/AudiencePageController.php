<?php

namespace App\Http\Controllers;

use App\Content\Homepage;
use App\Models\Application;
use App\Support\AdminPreview;
use App\Support\ApplicationForm;
use App\Support\ApplicationPrint;
use App\Support\ApplicationSpamProtection;
use App\Support\Audiences;
use Illuminate\Http\Response;

class AudiencePageController extends Controller
{
    public function __invoke(string $audience, Homepage $homepage): Response
    {
        $confirmation = request()->session()->pull('application_confirmations.'.$audience);
        $submitted = is_array($confirmation) && ($confirmation['expires_at'] ?? 0) >= time();

        // Organisations print their own answers from this one view; nothing is emailed.
        $printable = $submitted && $audience === 'organisations'
            ? Application::query()->where('audience', 'organisations')->find($confirmation['application_id'] ?? null)
            : null;

        $page = self::pageData($audience, $homepage->data());
        $response = response()->view('audience', [
            ...$page,
            'submitted' => $submitted,
            'printableAnswers' => $printable ? ApplicationPrint::answers($printable) : [],
            'emailSent' => $submitted && ($confirmation['email_sent'] ?? false),
        ]);

        // A closed form shown to an admin must never be cached and served to the public.
        return $page['adminPreview'] ? $response->header('Cache-Control', 'private, no-store') : $response;
    }

    /** Shared with the local messaging preview, which renders the same page. */
    public static function pageData(string $audience, array $data): array
    {
        $definition = Audiences::find($audience);
        $adminPreview = AdminPreview::active($definition, $data['edition']);
        $submissionToken = ApplicationForm::token(request(), $audience);

        return [
            ...$data,
            'audience' => $audience,
            'definition' => $definition,
            'applicationsOpen' => $adminPreview || $definition->isOpen($data['edition']),
            'adminPreview' => $adminPreview,
            'applicationUrl' => '#application',
            'submitted' => false,
            'printableAnswers' => [],
            'emailSent' => false,
            'submissionToken' => $submissionToken,
            'spamStartedToken' => ApplicationSpamProtection::issue(request(), (string) $data['edition']['year'], $audience, $submissionToken),
            'pageTitle' => $definition->pageTitle.' · Make a Mark Brussels',
            'pageDescription' => $definition->pageDescription,
        ];
    }
}
