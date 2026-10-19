<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\ValidationException;

class AdminAccounts
{
    public function create(User $actor, array $attributes): User
    {
        return DB::transaction(function () use ($actor, $attributes): User {
            $this->authorizeActor($actor);
            $data = $this->validate($attributes);

            $account = new User;
            $account->forceFill([
                ...$data,
                'is_admin' => true,
                'is_active' => $data['is_active'] ?? true,
                'is_super_admin' => $data['is_super_admin'] ?? false,
            ])->save();

            return $account;
        });
    }

    public function update(User $actor, User $account, array $attributes): User
    {
        return DB::transaction(function () use ($actor, $account, $attributes): User {
            // Lock super administrators in a stable order before either account.
            // Concurrent changes must not each rely on a super admin being removed.
            $activeSupers = User::query()->where('is_active', true)->where('is_super_admin', true)
                ->orderBy('id')->lockForUpdate()->get();

            $actor = $this->authorizeActor($actor);
            $account = User::query()->lockForUpdate()->findOrFail($account->getKey());
            $data = $this->validate($attributes, $account);
            $active = $data['is_active'] ?? $account->is_active;
            $super = $data['is_super_admin'] ?? $account->is_super_admin;

            if ($actor->is($account) && ! $active) {
                throw ValidationException::withMessages(['is_active' => 'You cannot deactivate your own account.']);
            }

            if ($actor->is($account) && ! $super) {
                throw ValidationException::withMessages(['is_super_admin' => 'You cannot remove your own super admin access.']);
            }

            if ($account->is_active && $account->is_super_admin && (! $active || ! $super) && $activeSupers->count() <= 1) {
                throw ValidationException::withMessages(['is_super_admin' => 'Keep at least one active super admin.']);
            }

            if (array_key_exists('password', $data)) {
                $data['remember_token'] = Str::random(60);
            }

            $account->forceFill([...$data, 'is_admin' => true])->save();

            return $account;
        });
    }

    private function authorizeActor(User $actor): User
    {
        $current = User::query()->lockForUpdate()->find($actor->getKey());

        if (! $current?->is_active || ! $current->is_super_admin) {
            throw new AuthorizationException;
        }

        return $current;
    }

    private function validate(array $attributes, ?User $account = null): array
    {
        if ($account && ($attributes['password'] ?? null) === '') {
            unset($attributes['password']);
        }

        return Validator::make($attributes, [
            'name' => [$account ? 'sometimes' : 'required', 'required', 'string', 'max:255'],
            'email' => [$account ? 'sometimes' : 'required', 'required', 'email', 'max:255', Rule::unique(User::class, 'email')->ignore($account)],
            'password' => [$account ? 'sometimes' : 'required', 'required', 'string', Password::min(12)->mixedCase()->numbers()->symbols()],
            'is_active' => ['sometimes', 'required', 'boolean'],
            'is_super_admin' => ['sometimes', 'required', 'boolean'],
        ])->validate();
    }
}
