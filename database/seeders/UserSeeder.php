<?php

namespace Database\Seeders;

use App\Models\Org;
use App\Models\User;
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
        User::create([
            'name' => 'Super Admin',
            'email' => 'superadmin@gmail.com',
            'password' => Hash::make('password'),
            'role' => 'superadmin',
            'status' => 'approved',
        ]);
        User::create([
            'name' => 'Admin',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'status' => 'approved',
        ]);

        User::factory()
            ->count(100)
            ->create()
            ->each(function ($user) {
                // Randomly assign roles
                $role = fake()->randomElement(['user', 'org']);
                $user->role = $role;
                $user->status = 'approved';
                $user->save();

                // If role is 'org', also create corresponding Org record
                if ($role === 'org') {
                    Org::create([
                        'name' => $user->name . ' Organization',
                    ]);
                }
            });
    }
}
