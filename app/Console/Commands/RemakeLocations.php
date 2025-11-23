<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class RemakeLocations extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'db:refill-locations';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fills locations into the database from the locations sqlite3 database';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        //
        $this->info('Remaking the locations: Clearing location tables...');

        Artisan::call('db:seed', [
            '--class' => 'LocationsSeeder'
        ], $this->output);

        $this->info('Locations Tables re-seeded successfully!');
    }
}
