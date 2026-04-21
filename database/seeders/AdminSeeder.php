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
        // Create default admin user
        Admin::create([
            'admin_num' => 'ADMIN001',
            'fname' => 'System',
            'lname' => 'Administrator',
            'email' => 'admin@labexam.com',
            'password' => Hash::make('admin123'),
            'status' => 'active',
        ]);

        $this->command->info('Default admin user created successfully!');
        $this->command->info('Email: admin@labexam.com');
        $this->command->info('Password: admin123');
    }
}
