<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Create Student
        $studentId = DB::table('students')->insertGetId([
            'stud_num' => 202100123,
            'fname' => 'John',
            'lname' => 'Smith',
            'gender' => 'Male',
            'bday' => '2002-05-15',
            'program' => 'BSCS',
            'year_level' => 3,
            'enrollment_status' => 'REGULAR',
            'email' => 'john.smith@ccs.edu',
            'number' => 123456789,
            'password' => Hash::make('password123'),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Create Student Account
        DB::table('students_acc')->insert([
            'stud_id' => $studentId,
            'is_active' => 1,
            'email' => 'john.smith@ccs.edu',
            'password' => Hash::make('password123'),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Create Faculty
        $facultyId = DB::table('faculty')->insertGetId([
            'fname' => 'Maria',
            'lname' => 'Garcia',
            'department' => 'CCS',
            'employment_type' => 'Full-time',
            'email' => 'maria.garcia@ccs.edu',
            'number' => 234567890,
            'password' => Hash::make('password123'),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Create Faculty Account
        DB::table('faculty_acc')->insert([
            'faculty_id' => $facultyId,
            'email' => 'maria.garcia@ccs.edu',
            'password' => Hash::make('password123'),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Create Admin
        $adminId = DB::table('admin')->insertGetId([
            'admin_num' => 'ADMIN001',
            'fname' => 'Robert',
            'lname' => 'Johnson',
            'email' => 'robert.johnson@ccs.edu',
            'phone' => 345678901,
            'role' => 'Super Admin',
            'status' => 'Active',
            'department' => 'CCS',
            'permissions' => json_encode(['all']),
            'last_login' => now(),
            'password' => Hash::make('password123'),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Create Admin Account
        DB::table('admin_acc')->insert([
            'admin_id' => $adminId,
            'email' => 'robert.johnson@ccs.edu',
            'password' => Hash::make('password123'),
            'is_active' => 1,
            'email_verified_at' => now(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $this->command->info('Sample users created successfully!');
        $this->command->info('Student: john.smith@ccs.edu / password123');
        $this->command->info('Faculty: maria.garcia@ccs.edu / password123');
        $this->command->info('Admin: robert.johnson@ccs.edu / password123');
    }
}
