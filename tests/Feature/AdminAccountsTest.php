<?php

namespace Tests\Feature;

use App\Filament\Resources\UserResource\Pages\CreateUser;
use App\Filament\Resources\UserResource\Pages\EditUser;
use App\Models\User;
use App\Services\AdminAccounts;
use Filament\Facades\Filament;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Livewire\Livewire;
use Tests\TestCase;

class AdminAccountsTest extends TestCase
{
    use RefreshDatabase;

    private function account(array $attributes = []): User
    {
        $account = User::factory()->create()->forceFill([
            'is_admin' => true,
            'is_super_admin' => false,
            'is_active' => true,
            ...$attributes,
        ]);
        $account->save();

        return $account;
    }

    public function test_super_admin_can_create_a_named_admin_with_a_hashed_password(): void
    {
        $actor = $this->account(['is_super_admin' => true]);
        $account = app(AdminAccounts::class)->create($actor, [
            'name' => 'New organiser',
            'email' => 'organiser@example.test',
            'password' => 'Unique-Password-421!',
            'is_super_admin' => false,
        ]);

        $this->assertSame('New organiser', $account->name);
        $this->assertTrue($account->is_admin);
        $this->assertTrue($account->is_active);
        $this->assertFalse($account->is_super_admin);
        $this->assertTrue(Hash::check('Unique-Password-421!', $account->password));
    }

    public function test_ordinary_admin_cannot_promote_an_account_through_the_service(): void
    {
        $actor = $this->account();
        $target = $this->account();

        try {
            app(AdminAccounts::class)->update($actor, $target, ['is_super_admin' => true]);
            $this->fail('Account promotion must require an active super admin.');
        } catch (AuthorizationException) {
            $this->assertFalse($target->fresh()->is_super_admin);
        }
    }

    public function test_an_inactive_or_stale_super_admin_cannot_create_accounts(): void
    {
        $actor = $this->account(['is_super_admin' => true]);
        User::whereKey($actor)->update(['is_active' => false]);

        $this->expectException(AuthorizationException::class);
        app(AdminAccounts::class)->create($actor, [
            'name' => 'Unauthorised', 'email' => 'blocked@example.test', 'password' => 'Unique-Password-421!',
        ]);
    }

    public function test_super_admin_cannot_deactivate_their_own_account(): void
    {
        $actor = $this->account(['is_super_admin' => true]);
        $this->account(['is_super_admin' => true]);

        try {
            app(AdminAccounts::class)->update($actor, $actor, ['is_active' => false]);
            $this->fail('Self-deactivation must be rejected.');
        } catch (ValidationException $exception) {
            $this->assertArrayHasKey('is_active', $exception->errors());
            $this->assertTrue($actor->fresh()->is_active);
        }
    }

    public function test_the_last_active_super_admin_cannot_demote_their_account(): void
    {
        $actor = $this->account(['is_super_admin' => true]);
        $this->account(['is_super_admin' => true, 'is_active' => false]);

        try {
            app(AdminAccounts::class)->update($actor, $actor, ['is_super_admin' => false]);
            $this->fail('The last active super admin must remain usable.');
        } catch (ValidationException $exception) {
            $this->assertArrayHasKey('is_super_admin', $exception->errors());
            $this->assertTrue($actor->fresh()->is_super_admin);
        }
    }

    public function test_super_admin_can_deactivate_another_admin_without_changing_their_password(): void
    {
        $actor = $this->account(['is_super_admin' => true]);
        $target = $this->account(['is_super_admin' => true]);
        $originalPassword = $target->password;

        $updated = app(AdminAccounts::class)->update($actor, $target, ['is_active' => false, 'password' => '']);

        $this->assertFalse($updated->is_active);
        $this->assertSame($originalPassword, $updated->password);
        $this->assertTrue($actor->fresh()->is_active);
    }

    public function test_duplicate_emails_and_weak_passwords_do_not_create_accounts(): void
    {
        $actor = $this->account(['is_super_admin' => true]);

        try {
            app(AdminAccounts::class)->create($actor, [
                'name' => 'Duplicate', 'email' => $actor->email, 'password' => 'weak',
            ]);
            $this->fail('Invalid account data must be rejected.');
        } catch (ValidationException $exception) {
            $this->assertArrayHasKey('email', $exception->errors());
            $this->assertArrayHasKey('password', $exception->errors());
            $this->assertDatabaseCount('users', 1);
        }
    }

    public function test_secure_interactive_command_creates_super_admin_without_printing_password(): void
    {
        $this->artisan('admin:create', ['--super-admin' => true])
            ->expectsQuestion('Name', 'Local organiser')
            ->expectsQuestion('Email address', 'local-organiser@example.test')
            ->expectsQuestion('Password (at least 12 characters, mixed case, number and symbol)', 'Unique-Password-421!')
            ->expectsQuestion('Confirm password', 'Unique-Password-421!')
            ->doesntExpectOutput('Unique-Password-421!')
            ->assertSuccessful();

        $account = User::where('email', 'local-organiser@example.test')->sole();
        $this->assertTrue($account->is_admin);
        $this->assertTrue($account->is_super_admin);
        $this->assertTrue($account->is_active);
        $this->assertTrue(Hash::check('Unique-Password-421!', $account->password));
    }

    public function test_super_admin_can_create_an_account_through_the_panel(): void
    {
        Filament::setCurrentPanel(Filament::getPanel('admin'));
        $this->actingAs($this->account(['is_super_admin' => true]));

        Livewire::test(CreateUser::class)
            ->fillForm([
                'name' => 'Panel organiser',
                'email' => 'panel-organiser@example.test',
                'password' => 'Unique-Password-421!',
                'is_active' => true,
                'is_super_admin' => false,
            ])
            ->call('create')
            ->assertHasNoFormErrors();

        $account = User::where('email', 'panel-organiser@example.test')->sole();
        $this->assertTrue($account->is_admin);
        $this->assertTrue(Hash::check('Unique-Password-421!', $account->password));
    }

    public function test_super_admin_can_update_another_account_through_the_panel(): void
    {
        Filament::setCurrentPanel(Filament::getPanel('admin'));
        $this->actingAs($this->account(['is_super_admin' => true]));
        $target = $this->account();

        Livewire::test(EditUser::class, ['record' => $target->getRouteKey()])
            ->fillForm(['name' => 'Updated organiser', 'is_super_admin' => true, 'is_active' => false])
            ->call('save')
            ->assertHasNoFormErrors();

        $this->assertSame('Updated organiser', $target->fresh()->name);
        $this->assertTrue($target->fresh()->is_super_admin);
        $this->assertFalse($target->fresh()->is_active);
    }

    public function test_panel_rejects_self_deactivation(): void
    {
        Filament::setCurrentPanel(Filament::getPanel('admin'));
        $actor = $this->account(['is_super_admin' => true]);
        $this->actingAs($actor);

        Livewire::test(EditUser::class, ['record' => $actor->getRouteKey()])
            ->fillForm(['is_active' => false])
            ->call('save')
            ->assertHasFormErrors(['is_active']);

        $this->assertTrue($actor->fresh()->is_active);
    }
}
