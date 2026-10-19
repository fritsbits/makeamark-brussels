<?php

namespace App\Console\Commands;

use Database\Seeders\AdminDemoSeeder;
use Illuminate\Console\Command;

class SeedAdminDemo extends Command
{
    protected $signature = 'admin:seed-demo';

    protected $description = 'Add fictional applications, reviews and teams for local admin testing';

    public function handle(): int
    {
        if (! app()->environment('local')) {
            $this->error('Admin demo data is available in the local environment only.');

            return self::FAILURE;
        }

        app(AdminDemoSeeder::class)->run();
        $this->info('Demo data ready for 2027: 6 organisations, 18 makers, 12 reviews and 2 starter teams.');
        $this->line('Existing applications, login credentials and edits to demo records are preserved.');
        $this->line(url('/admin/organisations'));

        return self::SUCCESS;
    }
}
