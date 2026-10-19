<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Validation\ValidationException;

#[Fillable(['name', 'email', 'password', 'is_admin', 'is_super_admin', 'is_active'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable implements FilamentUser
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    protected $attributes = ['is_admin' => false, 'is_super_admin' => false, 'is_active' => true];

    protected static function booted(): void
    {
        $guardLastSuper = function (User $user) {
            if ($user->getRawOriginal('is_active') && $user->getRawOriginal('is_super_admin')
                && ! static::where('is_super_admin', true)->where('is_active', true)->whereKeyNot($user->id)->exists()) {
                throw ValidationException::withMessages(['is_super_admin' => 'Keep at least one active super administrator.']);
            }
        };
        static::updating(function (User $user) use ($guardLastSuper) {
            if (! $user->is_active || ! $user->is_super_admin) {
                $guardLastSuper($user);
            }
        });
        static::deleting($guardLastSuper);
    }

    public function canAccessPanel(Panel $panel): bool
    {
        return $this->is_active && ($this->is_admin || $this->is_super_admin);
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_admin' => 'boolean',
            'is_super_admin' => 'boolean',
            'is_active' => 'boolean',
        ];
    }
}
