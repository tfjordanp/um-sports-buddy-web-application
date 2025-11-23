<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use Illuminate\Support\Facades\Artisan; // Import the Artisan facade

class RemakeDB extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'db:remake';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Drops all tables, runs migrations, and seeds the database.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        //
        $this->info('Remaking the database: Dropping tables...');
        
        Artisan::call('migrate:fresh', ['--seed' => true, '--force' => true], $this->output);

        $this->info('Database remade and seeded successfully!');

        return 0;
    }
}
