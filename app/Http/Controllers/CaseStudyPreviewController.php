<?php

namespace App\Http\Controllers;

use App\Content\Homepage;
use App\Content\SolumobCaseStudy;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class CaseStudyPreviewController extends Controller
{
    public function __invoke(Homepage $homepage, SolumobCaseStudy $caseStudy): Response
    {
        abort_unless($caseStudy->isVisible(), 404);

        $response = response()->view('case-studies.solumob', [
            'site' => $homepage->site(),
            'pageTitle' => 'Solumob Volontaires · Make a Mark Brussels',
            'pageDescription' => 'One day, four makers and a collection of communication materials to help Solumob attract volunteer drivers.',
        ]);

        return $response;
    }

    public function preview(): RedirectResponse
    {
        abort_unless(app()->environment('local', 'testing'), 404);

        return redirect(route('case-studies.solumob', absolute: false));
    }

    public function media(string $image, SolumobCaseStudy $caseStudy): BinaryFileResponse
    {
        abort_unless($caseStudy->isVisible(), 404);
        $path = $caseStudy->mediaPath($image);
        abort_if($path === null, 404);

        $response = response()->file($path);
        if (! $caseStudy->isPublished()) {
            $response->headers->set('X-Robots-Tag', 'noindex, nofollow');
            $response->headers->set('Cache-Control', 'private, no-store');
        }

        return $response;
    }
}
