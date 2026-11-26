<?php

namespace Tests\Feature;

use App\Mail\ApplicationReceived;
use App\Models\Application;
use App\Support\ApplicationSpamProtection;
use App\Support\Audiences;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\Factory;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\Concerns\SubmitsApplicationForms;
use Tests\TestCase;

class ApplicationSpamProtectionTest extends TestCase
{
    use RefreshDatabase, SubmitsApplicationForms;

    public static function providerFailures(): iterable
    {
        foreach (['makers', 'organisations', 'volunteers'] as $audience) {
            foreach (['missing', 'array', 'long', 'rejected', 'malformed', 'hostname', 'action', 'status', 'timeout', 'missing_secret', 'missing_sitekey', 'missing_hosts', 'truthy'] as $case) {
                yield $audience.' '.$case => [$audience, $case];
            }
        }
    }

    #[DataProvider('providerFailures')]
    public function test_unverified_new_applications_never_save_or_mail(string $audience, string $case): void
    {
        Mail::fake();
        $answers = $this->validApplication($audience);
        $result = ['success' => true, 'hostname' => 'forms.example.com', 'action' => 'application_'.$audience];
        switch ($case) {
            case 'missing': unset($answers['cf-turnstile-response']);
                break;
            case 'array': $answers['cf-turnstile-response'] = ['x'];
                break;
            case 'long': $answers['cf-turnstile-response'] = str_repeat('x', 2049);
                break;
            case 'rejected': $result['success'] = false;
                break;
            case 'truthy': $result['success'] = 'true';
                break;
            case 'hostname': $result['hostname'] = 'attacker.example.com';
                break;
            case 'action': $result['action'] = 'application_wrong';
                break;
            case 'missing_secret': config(['services.turnstile.secret_key' => '']);
                break;
            case 'missing_sitekey': config(['services.turnstile.site_key' => '']);
                break;
            case 'missing_hosts': config(['services.turnstile.allowed_hostnames' => []]);
                break;
        }
        Http::swap(new Factory);
        Http::preventStrayRequests();
        Http::fake(['*' => function () use ($case, $result) {
            if ($case === 'timeout') {
                throw new ConnectionException('fixture');
            }

            return Http::response($case === 'malformed' ? 'not json' : $result, $case === 'status' ? 503 : 200);
        }]);
        $this->post('/'.$audience.'/apply', $answers)->assertSessionHasErrors('submission')->assertSessionHasInput('email', 'person@example.com');
        $this->assertDatabaseCount('applications', 0);
        Mail::assertNothingSent();
        $this->assertNull(session()->getOldInput('cf-turnstile-response'));
    }

    public function test_provider_receives_only_secret_and_response_and_saved_retries_skip_verification(): void
    {
        Mail::fake();
        config(['mail.default' => 'resend', 'services.resend.key' => 'fixture', 'mail.from.address' => 'team@example.com']);
        $answers = $this->validApplication('volunteers');
        $this->post('/volunteers/apply', $answers)->assertSessionHasNoErrors();
        Http::assertSent(fn ($request) => $request->url() === 'https://challenges.cloudflare.com/turnstile/v0/siteverify'
            && $request->data() === ['secret' => 'secret-fixture', 'response' => 'fixture-volunteers']);
        $this->post('/volunteers/apply', ['_submission_token' => $answers['_submission_token'], 'email' => 'changed@example.com'])->assertSessionHasNoErrors();
        Http::assertSentCount(1);
        $this->assertSame('person@example.com', Application::sole()->email);
        Mail::assertSent(ApplicationReceived::class, 1);
        $fresh = $this->validApplication('volunteers');
        unset($fresh['cf-turnstile-response']);
        $this->post('/volunteers/apply', $fresh)->assertSessionHasErrors('submission');
        $this->assertDatabaseCount('applications', 1);
    }

    public function test_field_and_local_failures_do_not_spend_provider_tokens(): void
    {
        $answers = $this->validApplication('makers');
        $this->post('/makers/apply', [...$answers, 'email' => 'invalid'])->assertSessionHasErrors('email');
        $this->post('/makers/apply', [...$answers, 'website_confirmation' => 'bot'])->assertSessionHasErrors('submission');
        Http::assertNothingSent();
    }

    public function test_explicit_disabled_rollout_still_enforces_local_checks(): void
    {
        config(['services.turnstile.enabled' => false]);
        $answers = $this->validApplication('makers');
        unset($answers['cf-turnstile-response']);
        $this->post('/makers/apply', $answers)->assertSessionHasNoErrors();
        $this->assertDatabaseCount('applications', 1);
        Http::assertNothingSent();
    }

    public function test_rendered_forms_never_expose_server_secret(): void
    {
        foreach (['makers', 'organisations', 'volunteers'] as $audience) {
            $this->withoutVite()->get('/'.$audience)->assertOk()->assertDontSee('secret-fixture');
        }
    }

    public static function localFailures(): iterable
    {
        foreach (['makers', 'organisations', 'volunteers'] as $audience) {
            foreach (['missing_honeypot', 'array_honeypot', 'numeric_honeypot', 'filled_honeypot', 'missing_time', 'array_time', 'numeric_time', 'tampered', 'other_submission', 'other_audience', 'other_session', 'other_edition', 'future', 'zero', 'two'] as $case) {
                yield $audience.' '.$case => [$audience, $case];
            }
        }
    }

    #[DataProvider('localFailures')]
    public function test_local_failures_never_save_or_mail(string $audience, string $case): void
    {
        Mail::fake();
        $answers = $this->validApplication($audience);
        switch ($case) {
            case 'missing_honeypot': unset($answers['website_confirmation']);
                break;
            case 'array_honeypot': $answers['website_confirmation'] = [];
                break;
            case 'numeric_honeypot': $answers['website_confirmation'] = 0;
                break;
            case 'filled_honeypot': $answers['website_confirmation'] = 'spam';
                break;
            case 'missing_time': unset($answers['_form_started']);
                break;
            case 'array_time': $answers['_form_started'] = ['invalid'];
                break;
            case 'numeric_time': $answers['_form_started'] = 123;
                break;
            case 'tampered': $answers['_form_started'] .= 'x';
                break;
            case 'other_submission': $answers['_submission_token'] = $this->formMetadata($audience)['_submission_token'];
                break;
            case 'other_audience': $answers['_form_started'] = $this->formMetadata($audience === 'makers' ? 'volunteers' : 'makers')['_form_started'];
                break;
            case 'other_session':
                $this->withSession([Audiences::find($audience)->secretKey => str_repeat('z', 64)]);
                break;
            case 'other_edition':
                $request = request();
                $answers['_form_started'] = ApplicationSpamProtection::issue($request, '1900', $audience, $answers['_submission_token']);
                $this->travel(3)->seconds();
                break;
            case 'future': $this->travel(-4)->seconds();
                break;
            case 'zero': $this->travel(-3)->seconds();
                break;
            case 'two': $this->travel(-1)->seconds();
                break;
        }
        session()->save();
        $this->post('/'.$audience.'/apply', $answers)->assertSessionHasErrors('submission');
        $this->assertDatabaseCount('applications', 0);
        Mail::assertNothingSent();
        $this->assertNull(session()->getOldInput('_form_started'));
        $this->assertNull(session()->getOldInput('website_confirmation'));
        $this->assertNull(session()->getOldInput('cf-turnstile-response'));
    }

    public static function names(): iterable
    {
        foreach (['makers', 'organisations', 'volunteers'] as $audience) {
            foreach (['https://casino.example', ' HTTP://casino.example ', 'www.example.com', 'José', "O'Neill", 'Jean-Luc', '李明', 'Prince'] as $name) {
                yield $audience.' '.$name => [$audience, $name, ! str_contains(strtolower($name), 'http') && ! str_contains($name, 'www.')];
            }
        }
    }

    #[DataProvider('names')]
    public function test_person_names_and_three_second_boundary(string $audience, string $name, bool $valid): void
    {
        Mail::fake();
        $answers = $this->validApplication($audience);
        $field = $audience === 'organisations' ? 'contact_name' : 'name';
        $answers[$field] = $name;
        $response = $this->post('/'.$audience.'/apply', $answers);
        if (! $valid) {
            $response->assertSessionHasErrors($field);
            $this->assertDatabaseCount('applications', 0);
            Mail::assertNothingSent();

            return;
        }
        $response->assertSessionHasNoErrors();
        $this->assertDatabaseCount('applications', 1);
        foreach (['_form_started', 'website_confirmation', 'cf-turnstile-response', '_submission_token'] as $key) {
            $this->assertArrayNotHasKey($key, Application::sole()->answers);
        }
    }
}
