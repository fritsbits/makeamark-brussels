<?php

namespace App\Http\Controllers;

use App\Content\Homepage;
use App\Support\ApplicationForm;
use Illuminate\View\View;

class AudiencePageController extends Controller
{
    public function __invoke(string $audience, Homepage $homepage): View
    {
        abort_unless(in_array($audience, ['makers', 'organisations'], true), 404);

        $data = $homepage->data();
        $confirmation = request()->session()->pull('application_confirmations.'.$audience);
        $submitted = is_array($confirmation) && ($confirmation['expires_at'] ?? 0) >= time();

        return view('audience', [
            ...$data,
            'audience' => $audience,
            'makerHeroDate' => new \DateTimeImmutable($data['edition']['startDate']),
            'applicationUrl' => '#application',
            'submitted' => $submitted,
            'emailSent' => $submitted && ($confirmation['email_sent'] ?? false),
            'submissionToken' => ApplicationForm::token(request(), $audience),
            'pageTitle' => ($audience === 'makers' ? 'For makers' : 'For social organisations').' · Make a Mark Brussels',
            'pageDescription' => $audience === 'makers'
                ? 'Learn what to expect when you volunteer as a maker for the Make a Mark Brussels 2027 edition.'
                : 'Learn how your Brussels social organisation can bring a communication, design, or digital challenge to Make a Mark.',
        ]);
    }
}
