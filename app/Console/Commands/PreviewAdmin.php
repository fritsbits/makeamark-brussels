<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use RuntimeException;
use Throwable;

class PreviewAdmin extends Command
{
    protected $signature = 'admin:preview';

    protected $description = 'Prepare private, local-only administrator preview access';

    private const EMAIL = 'frederik.preview@makeamark.test';

    public function handle(): int
    {
        if (! app()->environment('local')) {
            $this->error('Preview accounts are available in the local environment only.');

            return self::FAILURE;
        }

        $path = Storage::disk('local')->path('admin-preview.json');
        $account = User::where('email', self::EMAIL)->first();
        if (file_exists($path) || is_link($path)) {
            $credentials = ! is_link($path) && is_file($path)
                ? json_decode(file_get_contents($path), true)
                : null;
            if (! $account || ! $account->is_active || ! $account->is_super_admin
                || ! is_array($credentials) || ($credentials['email'] ?? null) !== self::EMAIL
                || ! is_string($credentials['password'] ?? null)
                || ! Hash::check($credentials['password'], $account->password)) {
                return $this->conflict();
            }
            if (! chmod($path, 0600)) {
                $this->error('Unable to protect the local credentials file.');

                return self::FAILURE;
            }

            return $this->showPath($path);
        }
        if ($account) {
            return $this->conflict();
        }

        $createdFile = false;
        try {
            DB::transaction(function () use ($path, &$createdFile) {
                $password = Str::password(32);
                (new User)->forceFill([
                    'name' => 'Frederik · local preview',
                    'email' => self::EMAIL,
                    'password' => $password,
                    'is_admin' => true,
                    'is_super_admin' => true,
                    'is_active' => true,
                ])->save();

                $directory = dirname($path);
                if (! is_dir($directory) && ! mkdir($directory, 0700, true) && ! is_dir($directory)) {
                    throw new RuntimeException('Cannot create private storage.');
                }

                // Exclusive creation prevents a concurrent run from overwriting credentials.
                // Protect the empty file before writing any secret into it.
                $file = fopen($path, 'x');
                if ($file === false) {
                    throw new RuntimeException('Credentials file already exists or is unavailable.');
                }
                $createdFile = true;
                try {
                    if (! chmod($path, 0600)) {
                        throw new RuntimeException('Cannot protect credentials file.');
                    }
                    $contents = json_encode(['url' => url('/admin'), 'email' => self::EMAIL, 'password' => $password], JSON_PRETTY_PRINT | JSON_THROW_ON_ERROR)."\n";
                    if (fwrite($file, $contents) !== strlen($contents) || ! fflush($file)) {
                        throw new RuntimeException('Cannot write credentials file.');
                    }
                } finally {
                    fclose($file);
                }
            });
        } catch (Throwable) {
            if ($createdFile) {
                unlink($path);
            }
            $this->error('Preview setup failed; no account or credentials were changed. Resolve any local account or file conflict and retry.');

            return self::FAILURE;
        }

        return $this->showPath($path);
    }

    private function conflict(): int
    {
        $this->error('Local preview account/file conflict: existing credentials must match an active preview super admin. Nothing was changed.');

        return self::FAILURE;
    }

    private function showPath(string $path): int
    {
        try {
            $copyablePath = $this->writeCopyableCredentials($path);
        } catch (Throwable) {
            $this->error('Unable to prepare the copyable credentials file. The original account and JSON credentials have not been changed.');

            return self::FAILURE;
        }

        $this->line('Copyable credentials file: '.$copyablePath);
        $this->line('Credentials file: '.$path);
        $this->line(url('/admin'));

        return self::SUCCESS;
    }

    private function writeCopyableCredentials(string $source): string
    {
        $credentials = json_decode(file_get_contents($source), true, flags: JSON_THROW_ON_ERROR);
        $contents = 'URL: '.$credentials['url']."\nEmail: ".$credentials['email']."\nPassword: ".$credentials['password']."\n";
        $path = Storage::disk('local')->path('admin-preview.txt');

        if (file_exists($path) || is_link($path)) {
            if (is_link($path) || ! is_file($path) || file_get_contents($path) !== $contents || ! chmod($path, 0600)) {
                throw new RuntimeException('Copyable credentials file conflict.');
            }

            return $path;
        }

        $file = fopen($path, 'x');
        if ($file === false) {
            throw new RuntimeException('Cannot create copyable credentials file.');
        }
        try {
            if (! chmod($path, 0600) || fwrite($file, $contents) !== strlen($contents) || ! fflush($file)) {
                throw new RuntimeException('Cannot protect or write copyable credentials.');
            }
        } catch (Throwable $exception) {
            unlink($path);
            throw $exception;
        } finally {
            fclose($file);
        }

        return $path;
    }
}
