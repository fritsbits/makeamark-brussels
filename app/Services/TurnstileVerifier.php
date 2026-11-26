<?php

namespace App\Services;

use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class TurnstileVerifier
{
    public function verify(string $token, string $audience): void
    {
        $settings = config('services.turnstile');
        if (! $settings['enabled']) {
            return;
        }
        if (! is_string($settings['secret_key']) || trim($settings['secret_key']) === ''
            || ! is_string($settings['site_key']) || trim($settings['site_key']) === '' || empty($settings['allowed_hostnames'])) {
            $this->reject('configuration', true);
        }
        if ($token === '' || strlen($token) > 2048) {
            $this->reject('token');
        }
        try {
            $response = Http::asForm()->connectTimeout(3)->timeout(5)->post('https://challenges.cloudflare.com/turnstile/v0/siteverify', [
                'secret' => $settings['secret_key'], 'response' => $token,
            ]);
        } catch (ConnectionException) {
            $this->reject('connection', true);
        }
        $result = $response->json();
        if (! $response->successful() || ! is_array($result)) {
            $this->reject('provider', true);
        }
        if (($result['success'] ?? null) !== true
            || ! in_array($result['hostname'] ?? null, $settings['allowed_hostnames'], true)
            || ($result['action'] ?? null) !== 'application_'.$audience) {
            $this->reject('verification');
        }
    }

    private function reject(string $reason, bool $unavailable = false): never
    {
        Log::notice('Application verification rejected.', ['reason' => $reason]);
        throw ValidationException::withMessages(['submission' => $unavailable
            ? 'Verification is temporarily unavailable. Please try again or contact us.'
            : "We couldn't verify your submission. Please try again."]);
    }
}
