<?php

namespace App\Policies;

use App\Models\User;

class UserPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->is_active && $user->is_super_admin;
    }

    public function view(User $user, User $account): bool
    {
        return $this->viewAny($user);
    }

    public function create(User $user): bool
    {
        return $this->viewAny($user);
    }

    public function update(User $user, User $account): bool
    {
        return $this->viewAny($user);
    }

    public function delete(User $user, User $account): bool
    {
        return $this->viewAny($user) && $user->id !== $account->id
            && (! $account->is_active || ! $account->is_super_admin
                || User::where('is_active', true)->where('is_super_admin', true)->whereKeyNot($account->id)->exists());
    }
}
