<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Sport;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

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

        User::insert([
            'name' => 'Yod Tch',
            'email' => 'fakemail@gmail.com',
            'password' => Hash::make('password'),
        ]);

        // Define the list of sports to be inserted
        $sports = [
            [
                'name' => 'Football',
                'description' => 'A team sport played with a ball.',
                'profile_picture_url' => 'https://images.unsplash.com/photo-1579952363873-27f3bade9f55?q=80&w=735&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D',
            ],
            [
                'name' => 'Basketball', 
                'description' => 'A sport where two teams compete to shoot a ball through a hoop.',
                'profile_picture_url'=>'https://plus.unsplash.com/premium_photo-1685366454253-cb705836c5a8?q=80&w=1170&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D',
            ],
            [
                'name' => 'Tennis',
                'description' => 'A game played between two or four players using rackets and a ball.',
                'profile_picture_url'=> 'https://images.unsplash.com/photo-1545151414-8a948e1ea54f?q=80&w=687&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D',
            ],
            [
                'name' => 'Swimming',
                'description' => 'The action or activity of moving through water.',
                'profile_picture_url' => 'https://images.unsplash.com/photo-1560089000-7433a4ebbd64?q=80&w=735&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D',
            ],
            [
                'name' => 'Skateboard',
                'description' => 'Riding a skateboard for recreation or sport.',
                'profile_picture_url' => 'https://images.unsplash.com/photo-1499676988064-a3779763470e?q=80&w=722&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D',
            ],
            [
                'name' => 'Jogging',
                'description' => 'Running at a steady gentle pace as a form of physical exercise.',
                'profile_picture_url' => 'https://images.unsplash.com/photo-1739368732843-800f36a9b7d0?q=80&w=1170&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D',
            ],
            [
                'name' => 'Volley',
                'description' => 'A team sport in which two teams use their hands to hit a ball over a high net.',
                'profile_picture_url' => 'https://images.unsplash.com/photo-1612872087720-bb876e2e67d1?q=80&w=1007&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D',
            ],
            [
                'name' => 'Rugby',
                'description' => 'A team game played with an oval ball that may be kicked, carried, and passed by hand.',
                'profile_picture_url' => 'https://images.unsplash.com/photo-1480099225005-2513c8947aec?q=80&w=1003&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D',
            ],
            [
                'name' => 'Gym', 
                'description' => 'A facility for athletic activities or physical fitness.',
                'profile_picture_url' => 'https://images.unsplash.com/photo-1519505907962-0a6cb0167c73?q=80&w=1170&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D',
            ],
            // Add more sports as needed
        ];

        // Loop through the array and create records using the Sport model
        foreach ($sports as $sportData) {
            Sport::create($sportData);
        }
    }
}
