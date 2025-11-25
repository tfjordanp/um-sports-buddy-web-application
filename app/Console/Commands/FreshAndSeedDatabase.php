<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan; // Import the Artisan facade

class FreshAndSeedDatabase extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'db:fresh-seed';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clear all tables, remake the database and finally seed it.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        //
        $this->info('Starting database fresh and seed process...');

        // Run migrate:fresh
        $this->info('Running migrate:fresh...');
        // We use Artisan::call to execute existing commands programmatically
        Artisan::call('migrate:fresh', [], $this->output);
        $this->info('migrate:fresh finished.');

        // Run db:seed
        $this->info('Running db:seed...');
        Artisan::call('db:seed', [], $this->output);
        $this->info('db:seed finished.');

        $this->info('Database fresh and seed process completed successfully!');
    }
}
