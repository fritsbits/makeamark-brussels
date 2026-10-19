<?php

namespace App\Support;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

/** Compatibility adapter for existing maker-only callers. */
class MakerApplicationForm
{
    public static function normalize(array $input): array
    {
        return ApplicationForm::normalize($input, 'makers');
    }

    public static function token(Request $request): string
    {
        return ApplicationForm::token($request, 'makers');
    }

    public static function submissionKey(Request $request, int $editionYear): ?string
    {
        return ApplicationForm::submissionKey($request, 'makers', $editionYear);
    }

    public static function recover(Request $request, string $message): RedirectResponse
    {
        return ApplicationForm::recover($request, 'makers', $message);
    }
}
