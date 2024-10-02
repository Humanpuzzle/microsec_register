<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {       
        // User mappa tartalmának törlése
        Storage::deleteDirectory('users');
        

        $userData = [
            [
                'email' => 'customuser@example.com',
                'name' =>  'Jhon Doe',
                'birthdate' => '1990-01-01',
                'password' => Hash::make('securepassword123'),
                'email_verified_at' => now(),
                'remember_token' => Str::random(10),
            ],
            [
                'email' => 'jgreen@example.net',
                'name' =>  'Regan Emard',
                'birthdate' => '1999-09-13',
                'password' => Hash::make('securepassword124'),
                'email_verified_at' => now(),
                'remember_token' => Str::random(10),
            ],
            [
                'email' => 'renner.bethany@example.com',
                'name' =>  'Hayley Dickinson',
                'birthdate' => '1977-02-11',
                'password' => Hash::make('securepassword125'),
                'email_verified_at' => now(),
                'remember_token' => Str::random(10),
            ],
            [
                'email' => 'maddison50@example.org',
                'name' =>  'Seamus Boehm',
                'birthdate' => '1958-04-05',
                'password' => Hash::make('securepassword126'),
                'email_verified_at' => now(),
                'remember_token' => Str::random(10),
            ],
            [
                'email' => 'emard.bertrand@example.com',
                'name' =>  'Keenan Collier',
                'birthdate' => '2014-11-22',
                'password' => Hash::make('securepassword127'),
                'email_verified_at' => now(),
                'remember_token' => Str::random(10),
            ]        
        ];

        foreach ($userData as $key => $user) {
            // Felhasználó mentése az adatbázisba
            $new_user = User::create($user);
            // Adatok fájlban tárolása
            Storage::put('users/' . $new_user->id . '.json', json_encode($user));
        }
          
    }
}
