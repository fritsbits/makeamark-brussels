<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class AdminPreviewTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Storage::fake('local');
        Mail::fake();
    }

    protected function tearDown(): void
    {
        Storage::disk('local')->delete('admin-preview.json');
        Storage::disk('local')->delete('admin-preview.txt');
        parent::tearDown();
    }

    public function test_production_and_testing_environments_refuse_preview_accounts(): void
    {
        foreach (['production', 'testing'] as $environment) {
            $this->app->detectEnvironment(fn () => $environment);
            $this->assertSame(1, Artisan::call('admin:preview'));
            $this->assertStringContainsString('local environment', Artisan::output());
            $this->assertDatabaseCount('users', 0);
            Storage::disk('local')->assertMissing('admin-preview.json');
        }
    }

    public function test_local_preview_creates_private_credentials_without_printing_or_emailing_secrets(): void
    {
        $this->app->detectEnvironment(fn () => 'local');
        $this->assertSame(0, Artisan::call('admin:preview'));
        $output = Artisan::output();
        $credentials = json_decode(Storage::disk('local')->get('admin-preview.json'), true, flags: JSON_THROW_ON_ERROR);
        $account = User::sole();
        $this->assertSame('Frederik · local preview', $account->name);
        $this->assertSame('frederik.preview@makeamark.test', $account->email);
        $this->assertSame($account->email, $credentials['email']);
        $this->assertTrue($account->is_admin && $account->is_super_admin && $account->is_active);
        $this->assertGreaterThanOrEqual(24, strlen($credentials['password']));
        $this->assertTrue(Hash::check($credentials['password'], $account->password));
        $this->assertSame(url('/admin'), $credentials['url']);
        $path = Storage::disk('local')->path('admin-preview.json');
        clearstatcache(true, $path);
        $this->assertSame(0600, fileperms($path) & 0777);
        $this->assertStringContainsString($path, $output);
        $this->assertStringContainsString(url('/admin'), $output);
        $this->assertStringNotContainsString($credentials['password'], $output);
        $this->assertStringNotContainsString($credentials['email'], $output);
        Mail::assertNothingSent();
    }

    public function test_repeating_preview_reuses_the_account_and_credentials_without_rotation(): void
    {
        $this->app->detectEnvironment(fn () => 'local');
        $this->assertSame(0, Artisan::call('admin:preview'));
        $credentials = Storage::disk('local')->get('admin-preview.json');
        $passwordHash = User::sole()->password;
        $this->assertSame(0, Artisan::call('admin:preview'));
        $this->assertSame($credentials, Storage::disk('local')->get('admin-preview.json'));
        $this->assertSame($passwordHash, User::sole()->password);
        $this->assertDatabaseCount('users', 1);
    }

    public function test_existing_reserved_account_without_matching_file_is_not_taken_over(): void
    {
        $this->app->detectEnvironment(fn () => 'local');
        $account = User::factory()->create(['email' => 'frederik.preview@makeamark.test']);
        $before = $account->fresh()->getAttributes();
        $this->assertSame(1, Artisan::call('admin:preview'));
        $this->assertStringContainsString('conflict', Artisan::output());
        $this->assertSame($before, $account->fresh()->getAttributes());
        Storage::disk('local')->assertMissing('admin-preview.json');
    }

    public function test_conflicting_or_orphaned_credential_file_is_preserved_without_creating_an_account(): void
    {
        $this->app->detectEnvironment(fn () => 'local');
        foreach (['invalid-json', json_encode(['email' => 'frederik.preview@makeamark.test', 'password' => 'Unused-local-secret!'])] as $contents) {
            Storage::disk('local')->put('admin-preview.json', $contents);
            $this->assertSame(1, Artisan::call('admin:preview'));
            $this->assertStringContainsString('conflict', Artisan::output());
            $this->assertSame($contents, Storage::disk('local')->get('admin-preview.json'));
            $this->assertDatabaseCount('users', 0);
        }
    }

    public function test_changed_account_credentials_cannot_be_silently_reset(): void
    {
        $this->app->detectEnvironment(fn () => 'local');
        $this->assertSame(0, Artisan::call('admin:preview'));
        $contents = Storage::disk('local')->get('admin-preview.json');
        User::sole()->update(['password' => 'Changed-password-2435!']);
        $this->assertSame(1, Artisan::call('admin:preview'));
        $this->assertStringContainsString('conflict', Artisan::output());
        $this->assertSame($contents, Storage::disk('local')->get('admin-preview.json'));
        $this->assertTrue(Hash::check('Changed-password-2435!', User::sole()->password));
    }

    public function test_preview_provides_a_copyable_password_without_json_escaping(): void
    {
        $this->app->detectEnvironment(fn () => 'local');
        $password = 'Copyable\\Local-Password-2027!';
        $account = User::factory()->create([
            'email' => 'frederik.preview@makeamark.test', 'password' => $password,
            'is_admin' => true, 'is_super_admin' => true, 'is_active' => true,
        ]);
        Storage::disk('local')->put('admin-preview.json', json_encode([
            'url' => url('/admin'), 'email' => $account->email, 'password' => $password,
        ], JSON_PRETTY_PRINT));

        $this->assertSame(0, Artisan::call('admin:preview'));
        Storage::disk('local')->assertExists('admin-preview.txt');
        $output = Artisan::output();
        $contents = Storage::disk('local')->get('admin-preview.txt');
        preg_match('/^Password: (.+)$/m', $contents, $matches);
        $this->assertSame($password, $matches[1]);
        $this->assertTrue(auth()->attempt(['email' => $account->email, 'password' => $matches[1]]));
        $this->assertSame(0600, fileperms(Storage::disk('local')->path('admin-preview.txt')) & 0777);
        $this->assertStringNotContainsString($password, $output);
        $this->assertStringContainsString('admin-preview.txt', $output);
    }
}
