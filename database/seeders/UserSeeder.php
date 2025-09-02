<?php

namespace Database\Seeders;

use App\Models\Org;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */


public function run(): void
{
    // Seed fixed admin accounts
    User::create([
        'name' => 'Super Admin',
        'email' => 'superadmin@gmail.com',
        'username' => 'superadmin',
        'password' => Hash::make('password'),
        'role' => 'superadmin',
        'status' => 'approved',
    ]);

    User::create([
        'name' => 'Admin',
        'email' => 'admin@gmail.com',
        'username' => 'admin',
        'password' => Hash::make('password'),
        'role' => 'admin',
        'status' => 'approved',
    ]);

    User::create([
        'name' => 'org',
        'email' => 'org@gmail.com',
        'username' => 'org',
        'password' => Hash::make('password'),
        'role' => 'org',
        'status' => 'approved',
    ]);

    Org::create([
        'name' => 'org Organization',
    ]);

    User::create([
        'name' => 'user',
        'email' => 'user@gmail.com',
        'username' => 'user',
        'password' => Hash::make('password'),
        'role' => 'user',
        'status' => 'approved',
    ]);

    User::create([
        'name' => 'user1',
        'email' => 'user1@gmail.com',
        'username' => 'user1',
        'password' => Hash::make('password'),
        'role' => 'user',
        'status' => 'approved',
    ]);

    // // Seed 100 users with email = student#@pampangastateu.edu.ph
    User::factory()
        ->count(100)
        ->create()
        ->each(function ($user) {
            // Generate fake student number (8-digit number)
            $studentNumber = fake()->unique()->numberBetween(2000000000, 9999999999);
            $user->email = $studentNumber . '@pampangastateu.edu.ph';
            $user->username = (string) $studentNumber;

            // Assign random role and status
            $role = fake()->randomElement(['user', 'org']);
            $user->role = $role;
            $user->status = 'approved';
            $user->save();

            // If role is 'org', create Org
            if ($role === 'org') {
                Org::create([
                    'name' => $user->name . ' Organization',
                ]);
            }
        });
}

}
