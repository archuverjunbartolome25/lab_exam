<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class CreateTestUsersSeeder extends Seeder
{
    public function run(): void
    {
        // Create Student User
        User::create([
            'name' => 'John Smith',
            'email' => 'john.smith@ccs.edu',
            'password' => Hash::make('password123'),
            'email_verified_at' => now(),
        ]);

        // Create Faculty User
        User::create([
            'name' => 'Maria Garcia',
            'email' => 'maria.garcia@ccs.edu',
            'password' => Hash::make('password123'),
            'email_verified_at' => now(),
        ]);

        // Create Admin User
        User::create([
            'name' => 'Robert Johnson',
            'email' => 'robert.johnson@ccs.edu',
            'password' => Hash::make('password123'),
            'email_verified_at' => now(),
        ]);
    }
}
