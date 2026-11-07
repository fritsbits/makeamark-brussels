<?php

namespace App\Http\Controllers;

use App\Content\Homepage;
use App\Models\Application;
use App\Support\ApplicationForm;
use App\Support\ApplicationPrint;
use App\Support\Audiences;
use Illuminate\View\View;

class AudiencePageController extends Controller
{
    public function __invoke(string $audience, Homepage $homepage): View
    {
        $confirmation = request()->session()->pull('application_confirmations.'.$audience);
        $submitted = is_array($confirmation) && ($confirmation['expires_at'] ?? 0) >= time();

        // Organisations print their own answers from this one view; nothing is emailed.
        $printable = $submitted && $audience === 'organisations'
            ? Application::query()->where('audience', 'organisations')->find($confirmation['application_id'] ?? null)
            : null;

        return view('audience', [
            ...self::pageData($audience, $homepage->data()),
            'submitted' => $submitted,
            'printableAnswers' => $printable ? ApplicationPrint::answers($printable) : [],
            'emailSent' => $submitted && ($confirmation['email_sent'] ?? false),
        ]);
    }

    /** Shared with the local messaging preview, which renders the same page. */
    public static function pageData(string $audience, array $data): array
    {
        $definition = Audiences::find($audience);

        return [
            ...$data,
            'audience' => $audience,
            'definition' => $definition,
            'applicationsOpen' => $definition->isOpen($data['edition']),
            'applicationUrl' => '#application',
            'submitted' => false,
            'printableAnswers' => [],
            'emailSent' => false,
            'submissionToken' => ApplicationForm::token(request(), $audience),
            'pageTitle' => $definition->pageTitle.' · Make a Mark Brussels',
            'pageDescription' => $definition->pageDescription,
        ];
    }
}
