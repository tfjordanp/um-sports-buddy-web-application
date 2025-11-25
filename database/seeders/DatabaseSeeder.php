<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Sport;
use App\Models\UserSportPreferences;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use Illuminate\Support\Arr;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        /*User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);*/

        $locations = [19140,19169,19266];
        //baf, douala, yaounde


        User::insert([
            'name' => 'Yod Tch',
            'email' => 'fakemail@gmail.com',
            'password' => Hash::make('password'),
        ]);

        $dUsers = [
            User::create([
                'name' => 'Sor Pins',
                'email' => 'sorpins@gmail.com',
                'password' => Hash::make('password'),
                'location_id' => $locations[1], 
            ]),
            User::create([
                'name' => 'Mark Fik',
                'email' => 'markfik@gmail.com',
                'password' => Hash::make('password'),
                'location_id' => $locations[0],
            ]),
            User::create([
                'name' => 'John Chris',
                'email' => 'johncris@gmail.com',
                'password' => Hash::make('password'),
                'location_id' => $locations[1],
            ]),
            User::create([
                'name' => 'Arne Bush',
                'email' => 'anbush@gmail.com',
                'password' => Hash::make('password'),
                'location_id' => $locations[2],
            ])
        ];

        $users = User::factory(100)->create();



        // Define the list of sports to be inserted
        $sports = [
            [
                'name' => 'Football',
                'description' => 'A team sport played with a ball.',
                'profile_picture_url' => 'https://images.unsplash.com/photo-1579952363873-27f3bade9f55?q=80&w=735&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D',
                'location_details' => [
                    'Field 3, behind the grandstands',
                    'The green area next to the playground',
                ]
            ],
            [
                'name' => 'Basketball', 
                'description' => 'A sport where two teams compete to shoot a ball through a hoop.',
                'profile_picture_url'=>'https://plus.unsplash.com/premium_photo-1685366454253-cb705836c5a8?q=80&w=1170&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D',
                'location_details' => [
                    'The large open field near the parking lot',
                    'The green area next to the playground',
                ]
            ],
            [
                'name' => 'Tennis',
                'description' => 'A game played between two or four players using rackets and a ball.',
                'profile_picture_url'=> 'https://images.unsplash.com/photo-1545151414-8a948e1ea54f?q=80&w=687&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D',
                'location_details' => [
                    'Tennis court #5, back corner',
                ]
            ],
            [
                'name' => 'Swimming',
                'description' => 'The action or activity of moving through water.',
                'profile_picture_url' => 'https://images.unsplash.com/photo-1560089000-7433a4ebbd64?q=80&w=735&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D',
                'location_details' => [
                    'By the old oak tree near the river bank',
                    'Main swimming pool, Lane 4'
                ]
            ],
            [
                'name' => 'Skateboard',
                'description' => 'Riding a skateboard for recreation or sport.',
                'profile_picture_url' => 'https://images.unsplash.com/photo-1499676988064-a3779763470e?q=80&w=722&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D',
                'location_details' => [
                    'Bottom of the main ski slope near the lodge',
                    'Meet at the fountain in the center of the park'
                ]
            ],
            [
                'name' => 'Jogging',
                'description' => 'Running at a steady gentle pace as a form of physical exercise.',
                'profile_picture_url' => 'https://images.unsplash.com/photo-1739368732843-800f36a9b7d0?q=80&w=1170&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D',
                'location_details' => [
                    'Bottom of the main ski slope near the lodge',
                    'Local High School Track'
                ]
            ],
            [
                'name' => 'Volley',
                'description' => 'A team sport in which two teams use their hands to hit a ball over a high net.',
                'profile_picture_url' => 'https://images.unsplash.com/photo-1612872087720-bb876e2e67d1?q=80&w=1007&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D',
                'location_details' => [
                    'Next to the public beach volleyball nets',
                    'Courts 1 and 2 (near the entrance)'
                ]
            ],
            [
                'name' => 'Rugby',
                'description' => 'A team game played with an oval ball that may be kicked, carried, and passed by hand.',
                'profile_picture_url' => 'https://images.unsplash.com/photo-1480099225005-2513c8947aec?q=80&w=1003&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D',
                'location_details' => [
                    'Just past the main stadium entrance, near the snack bar',
                ]
            ],
            [
                'name' => 'Gym', 
                'description' => 'A facility for athletic activities or physical fitness.',
                'profile_picture_url' => 'https://images.unsplash.com/photo-1519505907962-0a6cb0167c73?q=80&w=1170&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D',
                'location_details' => [
                    'The local climbing gym',
                    'Inside the main gymnasium, court B',
                ]
            ],
            // Add more sports as needed
        ];

        // Loop through the array and create records using the Sport model
        foreach ($sports as $sportData) {
            Sport::create(Arr::only($sportData,['name','description','profile_picture_url']));
        }

        
        for ($i = 1 ; $i <= count($sports) ; $i++){
            foreach ($dUsers as $dUser){
                UserSportPreferences::insert([
                    'user_id' => $dUser->id,
                    'sport_id' => $i,
                    'level' => rand(1,4),
                ]);
            }
        }

        foreach($users as $user){
            UserSportPreferences::insert([
                'user_id' => $user->id,
                'sport_id' => random_int(1,count($sports)),
                'level' => random_int(1,4),
            ]);
        }

        foreach($users->concat($dUsers) as $user){
            $sport = $user->preferredSports()->inRandomOrder()->first();
            $details = $sports[$sport->id-1]['location_details'];
            $i = array_rand($details,1);
            $loc_d = $details[$i];
            $user->organizedEvents()->create([
                    'title' => 'Calcio',
                    'description' => 'Street Budding',
                    'location_details' => $loc_d,
                    'sport_id' => $sport->id,
                    'location_id' => $user->location_id,
                    'scheduled_date_time' => Carbon::now()->addDays(rand(1,30)),
                    'max_participants' => rand(2,50),
                    'profile_picture_url' => null
            ]);
        }


    }
}
