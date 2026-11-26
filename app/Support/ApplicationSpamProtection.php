<?php

namespace App\Support;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class ApplicationSpamProtection
{
    public static function issue(Request $request, string $edition, string $audience, string $submissionToken): string
    {
        $issued = (string) now()->timestamp;

        return $issued.'.'.self::signature($request, $edition, $audience, $submissionToken, $issued);
    }

    public static function validateLocal(Request $request, string $edition, string $audience, string $submissionToken): void
    {
        $input = $request->all();
        if (! array_key_exists('website_confirmation', $input) || ! in_array($input['website_confirmation'], ['', null], true)) {
            self::reject('honeypot');
        }

        $token = $request->input('_form_started');
        if (! is_string($token) || ! preg_match('/\A([0-9]{1,12})\.([a-f0-9]{64})\z/', $token, $parts)
            || ! hash_equals(self::signature($request, $edition, $audience, $submissionToken, $parts[1]), $parts[2])) {
            self::reject('timing_signature');
        }

        if (now()->timestamp - (int) $parts[1] < 3) {
            self::reject('timing_age');
        }
    }

    private static function signature(Request $request, string $edition, string $audience, string $submissionToken, string $issued): string
    {
        $secret = $request->session()->get(Audiences::find($audience)->secretKey);

        return hash_hmac('sha256', json_encode(['application-timing-v1', $edition, $audience, $submissionToken, $issued], JSON_THROW_ON_ERROR), is_string($secret) ? $secret : '');
    }

    private static function reject(string $reason): never
    {
        Log::notice('Application spam check rejected.', ['reason' => $reason]);
        throw ValidationException::withMessages(['submission' => "We couldn't verify your submission. Please try again."]);
    }
}
