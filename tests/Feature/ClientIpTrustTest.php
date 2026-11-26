<?php

namespace Tests\Feature;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Tests\TestCase;

class ClientIpTrustTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        Route::get('/_test/client-ip', fn (Request $request) => response()->json(['ip' => $request->ip()]))
            ->middleware('throttle:2,1');
    }

    public function test_direct_clients_cannot_replace_their_ip_with_forwarded_headers(): void
    {
        foreach (['letsmakeamark.brussels', 'makeamark-brussels.on-forge.com'] as $index => $host) {
            $peer = '198.51.100.'.(20 + $index);

            $this->withServerVariables(['REMOTE_ADDR' => $peer])
                ->getJson('https://'.$host.'/_test/client-ip', ['X-Forwarded-For' => '203.0.113.99'])
                ->assertOk()->assertJsonPath('ip', $peer);
        }
    }

    public function test_changing_forwarded_ips_does_not_bypass_the_rate_limit(): void
    {
        foreach (['letsmakeamark.brussels', 'makeamark-brussels.on-forge.com'] as $index => $host) {
            $peer = '198.51.100.'.(40 + $index);

            for ($attempt = 1; $attempt <= 3; $attempt++) {
                $this->withServerVariables(['REMOTE_ADDR' => $peer])
                    ->getJson('https://'.$host.'/_test/client-ip', ['X-Forwarded-For' => '203.0.113.'.$attempt])
                    ->assertStatus($attempt <= 2 ? 200 : 429);
            }

            $this->withServerVariables(['REMOTE_ADDR' => '198.51.100.'.(60 + $index)])
                ->getJson('https://'.$host.'/_test/client-ip', ['X-Forwarded-For' => '203.0.113.1'])
                ->assertOk();
        }
    }
}
