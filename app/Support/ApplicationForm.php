<?php

namespace App\Support;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ApplicationForm
{
    /** Bound flashed drafts and replace invalid shapes before rendering either form. */
    public static function normalize(array $input, string $audience): array
    {
        $fields = match ($audience) {
            'makers' => [
                'name' => 255, 'email' => 255, 'motivation' => 5000, 'referral' => 1000,
                'dutch' => 255, 'english' => 255, 'french' => 255, 'other_languages' => 1000,
                'portfolio' => 2000, 'social_media' => 2000, 'main_skills' => 3000,
                'backup_skills' => 3000, 'other_skills' => 3000, 'team_preferences' => 2000,
                'meeting_availability' => 2000,
            ],
            'organisations' => [
                'organisation_name' => 255, 'email' => 255, 'mission' => 5000,
                'social_media' => 2000, 'website' => 2000, 'challenge' => 5000,
                'contact_name' => 255, 'phone' => 80,
            ],
        };
        $values = [];

        foreach ($fields as $field => $limit) {
            $values[$field] = is_string($input[$field] ?? null) ? mb_substr($input[$field], 0, $limit) : '';
        }

        $checkboxes = $audience === 'makers'
            ? ['date_confirmed', 'full_commitment']
            : ['youth_brussels_eligibility', 'assets_ready', 'planning_meeting', 'feedback_session', 'evening_presentation'];

        foreach ($checkboxes as $field) {
            $values[$field] = in_array($input[$field] ?? null, ['yes', 'on', '1', 1, true, 'true'], true) ? '1' : '';
        }

        $list = $audience === 'makers' ? 'themes' : 'languages';
        $values[$list] = is_array($input[$list] ?? null)
            ? array_values(array_filter(array_slice($input[$list], 0, $audience === 'makers' ? 10 : 3), fn ($value) => is_string($value) && mb_strlen($value) <= 255))
            : [];

        if (self::wellFormedToken($input['_submission_token'] ?? null)) {
            $values['_submission_token'] = $input['_submission_token'];
        }

        return $values;
    }

    /** Independent nonces avoid an ever-growing registry; separate secrets bind each audience. */
    public static function token(Request $request, string $audience): string
    {
        $secretKey = self::secretKey($audience);
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
        $secret = $request->session()->get(self::secretKey($audience));

        // Keep existing maker replay keys stable across the shared-helper rollout.
        $scope = $audience === 'makers' ? (string) $editionYear : $audience.':'.$editionYear;

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

    private static function secretKey(string $audience): string
    {
        return match ($audience) {
            'makers' => 'maker_submission_secret',
            'organisations' => 'organisation_submission_secret',
        };
    }

    private static function signature(string $nonce, string $secret, string $audience): string
    {
        // Maker forms already in browsers remain valid; their secret is audience-specific.
        return hash_hmac('sha256', $audience === 'makers' ? $nonce : $audience.':'.$nonce, $secret);
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
