<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UpdateUserPasswordsSeeder extends Seeder
{
    public function run(): void
    {
        // Update Student with password
        DB::table('students')
            ->where('email', 'john.smith@ccs.edu')
            ->update([
                'password' => Hash::make('password123'),
                'updated_at' => now(),
            ]);

        // Update Faculty with password
        DB::table('faculty')
            ->where('email', 'maria.garcia@ccs.edu')
            ->update([
                'password' => Hash::make('password123'),
                'updated_at' => now(),
            ]);

        // Update Admin with password
        DB::table('admin')
            ->where('email', 'robert.johnson@ccs.edu')
            ->update([
                'password' => Hash::make('password123'),
                'updated_at' => now(),
            ]);

        $this->command->info('User passwords updated successfully!');
        $this->command->info('All users can now login with: password123');
    }
}
