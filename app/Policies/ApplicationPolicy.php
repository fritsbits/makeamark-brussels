<?php

namespace App\Policies;

use App\Models\Application;
use App\Models\User;

class ApplicationPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->is_active && ($user->is_admin || $user->is_super_admin);
    }

    public function view(User $user, Application $application): bool
    {
        return $this->viewAny($user);
    }

    public function create(User $user): bool
    {
        return false;
    }

    public function update(User $user, Application $application): bool
    {
        return $this->viewAny($user);
    }

    public function delete(User $user, Application $application): bool
    {
        return false;
    }
}
