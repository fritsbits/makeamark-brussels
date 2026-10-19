<?php

namespace App\Policies;

use App\Models\ApplicationReview;
use App\Models\User;

class ApplicationReviewPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->is_active && ($user->is_admin || $user->is_super_admin);
    }

    public function view(User $user, ApplicationReview $review): bool
    {
        return $this->viewAny($user);
    }

    public function create(User $user): bool
    {
        return $this->viewAny($user);
    }

    public function update(User $user, ApplicationReview $review): bool
    {
        return $this->viewAny($user) && $review->user_id === $user->id;
    }

    public function delete(User $user, ApplicationReview $review): bool
    {
        return $this->update($user, $review);
    }
}
