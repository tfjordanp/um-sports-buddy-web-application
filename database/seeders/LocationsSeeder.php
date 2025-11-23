<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use Illuminate\Support\Facades\DB;

use App\Models\Country;
use App\Models\State;
use App\Models\City;

class LocationsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        
        $connMain = DB::connection('sqlite');
        $connMain->table('Cities')->truncate();
        $connMain->table('States')->truncate();
        $connMain->table('Countries')->truncate();

        //Takes alot of time !!!
        $conn = DB::connection('locations_sqlite');

        $countries =        $conn
                            ->table('countries')
                            ->get();

        $len = $countries->count();
        $i = 0;

        foreach ($countries as $c) {
            $country = Country::create(["name" => $c->name]);
            $states = $conn->table('states')
            ->where('states.country_id','=',$c->id)
            ->get();
            foreach ($states as $s) {
                $state = State::create([
                    "name" => $s->name,
                    "country_id" => $country->id,
                ]);

                 $cities = $conn->table('cities')
                ->where('cities.state_id','=',$s->id)
                ->get();

                foreach ($cities as $ci) {
                    City::create([
                        "name" => $ci->name,
                        "state_id" => $state->id,
                    ]);
                }
            }


            $i = $i + 1;
            echo("Wrote $i/$len countries - $c->name\n");
        }

        
    }
}
