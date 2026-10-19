<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class CreateAdmin extends Command
{
    protected $signature = 'admin:create {--name= : Account holder name} {--email= : Account holder email address} {--super-admin : Grant account-management access}';

    protected $description = 'Create a named administrator with a securely prompted password';

    public function handle(): int
    {
        if (! $this->input->isInteractive()) {
            $this->error('Run this command interactively so the password can be entered securely.');

            return self::FAILURE;
        }

        $attributes = [
            'name' => $this->option('name') ?: $this->ask('Name'),
            'email' => $this->option('email') ?: $this->ask('Email address'),
            'password' => $this->secret('Password (at least 12 characters, mixed case, number and symbol)', false),
            'password_confirmation' => $this->secret('Confirm password', false),
        ];

        $validator = Validator::make($attributes, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique(User::class, 'email')],
            'password' => ['required', 'string', 'confirmed', Password::min(12)->mixedCase()->numbers()->symbols()],
        ]);

        if ($validator->fails()) {
            foreach ($validator->errors()->all() as $message) {
                $this->error($message);
            }

            return self::FAILURE;
        }

        (new User)->forceFill([
            'name' => $attributes['name'],
            'email' => $attributes['email'],
            'password' => $attributes['password'],
            'is_admin' => true,
            'is_super_admin' => (bool) $this->option('super-admin'),
            'is_active' => true,
        ])->save();

        $this->line(url('/admin'));

        return self::SUCCESS;
    }
}
