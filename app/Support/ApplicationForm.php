<?php

namespace App\Support;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ApplicationForm
{
    /** Bound flashed drafts and replace invalid shapes before rendering any form. */
    public static function normalize(array $input, string $audience): array
    {
        $definition = Audiences::find($audience);
        $values = [];

        foreach ($definition->fields as $field => $limit) {
            $values[$field] = is_string($input[$field] ?? null) ? mb_substr($input[$field], 0, $limit) : '';
        }

        foreach ($definition->checkboxes as $field) {
            $values[$field] = in_array($input[$field] ?? null, ['yes', 'on', '1', 1, true, 'true'], true) ? '1' : '';
        }

        foreach ($definition->lists as $list => $limit) {
            $values[$list] = is_array($input[$list] ?? null)
                ? array_values(array_filter(array_slice($input[$list], 0, $limit), fn ($value) => is_string($value) && mb_strlen($value) <= 255))
                : [];
        }

        if (self::wellFormedToken($input['_submission_token'] ?? null)) {
            $values['_submission_token'] = $input['_submission_token'];
        }

        return $values;
    }

    /** Independent nonces avoid an ever-growing registry; separate secrets bind each audience. */
    public static function token(Request $request, string $audience): string
    {
        $secretKey = Audiences::find($audience)->secretKey;
        $secret = $request->session()->get($secretKey);

        if (! is_string($secret) || strlen($secret) !== 64) {
            $secret = Str::random(64);
            $request->session()->put($secretKey, $secret);
        }

        $old = $request->session()->getOldInput('_submission_token');

        if (self::validToken($old, $secret, $audience)) {
            return $old;
        }

        $nonce = bin2hex(random_bytes(16));

        return $nonce.'.'.self::signature($nonce, $secret, $audience);
    }

    public static function submissionKey(Request $request, string $audience, int $editionYear): ?string
    {
        $token = $request->input('_submission_token');
        $secret = $request->session()->get(Audiences::find($audience)->secretKey);

        // Keep existing maker replay keys stable across the shared-helper rollout.
        $scope = Audiences::find($audience)->tokenScope((string) $editionYear);

        return self::validToken($token, $secret, $audience)
            ? hash_hmac('sha256', $scope.':'.$token, $secret)
            : null;
    }

    public static function recover(Request $request, string $audience, string $message): RedirectResponse
    {
        return redirect()->route('audience.show', $audience)
            ->withFragment('application')
            ->withErrors(['submission' => $message])
            ->withInput(self::normalize($request->input(), $audience));
    }

    private static function signature(string $nonce, string $secret, string $audience): string
    {
        // Maker forms already in browsers remain valid; their secret is audience-specific.
        return hash_hmac('sha256', Audiences::find($audience)->tokenScope($nonce), $secret);
    }

    private static function validToken(mixed $token, mixed $secret, string $audience): bool
    {
        return self::wellFormedToken($token) && is_string($secret) && strlen($secret) === 64
            && hash_equals(self::signature(substr($token, 0, 32), $secret, $audience), substr($token, 33));
    }

    private static function wellFormedToken(mixed $token): bool
    {
        return is_string($token) && preg_match('/\A[a-f0-9]{32}\.[a-f0-9]{64}\z/', $token) === 1;
    }
}
