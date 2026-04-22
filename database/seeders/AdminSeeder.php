<?php

namespace Database\Seeders;

use App\Models\Admin;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Check if admin user already exists
        $existingAdmin = \App\Models\Admin::where('email', 'admin@ccs.edu')->first();
        
        if (!$existingAdmin) {
            \App\Models\Admin::create([
                'user_num' => 'ADMIN001',
                'fname' => 'System',
                'lname' => 'Administrator',
                'email' => 'admin@ccs.edu',
                'password' => bcrypt('admin123')
            ]);
            
            $this->command->info('Admin user created successfully!');
            $this->command->info('Email: admin@ccs.edu');
            $this->command->info('Password: admin123');
        } else {
            $this->command->info('Admin user already exists.');
        }
    }
}
