<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AcademicTablesSeeder extends Seeder
{
    public function run(): void
    {
        // Faculty insertion skipped due to existing records

        // Insert Courses IT
        DB::table('courses_it')->insert([
            [
                'course' => 'Introduction to Computing',
                'course_code' => 'CCS101',
                'description' => 'Fundamental concepts of computing and information technology',
                'credits' => 3,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'course' => 'Programming Basics',
                'course_code' => 'CCS102',
                'description' => 'Introduction to programming concepts and problem solving',
                'credits' => 3,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        // Insert Courses CS
        DB::table('courses_cs')->insert([
            [
                'course' => 'Data Structures and Algorithms',
                'course_code' => 'CCS201',
                'description' => 'Advanced data structures and algorithm analysis',
                'credits' => 4,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'course' => 'Web Development',
                'course_code' => 'CCS202',
                'description' => 'Modern web development technologies and frameworks',
                'credits' => 3,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        // Insert Sections IT (without faculty_id for now)
        DB::table('sections_it')->insert([
            [
                'section' => '1IT-A',
                'courses' => json_encode([1]),
                'faculty_id' => null,
                'schedule' => 'MWF 8:00-9:30 AM',
                'room' => 'Lab 101',
                'max_students' => 40,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'section' => '1IT-B',
                'courses' => json_encode([2]),
                'faculty_id' => null,
                'schedule' => 'TTH 10:00-11:30 AM',
                'room' => 'Lab 102',
                'max_students' => 40,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        // Insert Sections CS (without faculty_id for now)
        DB::table('sections_cs')->insert([
            [
                'section' => '1CS-A',
                'courses' => json_encode([1]),
                'faculty_id' => null,
                'schedule' => 'MWF 1:00-2:30 PM',
                'room' => 'Lab 201',
                'max_students' => 35,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'section' => '1CS-B',
                'courses' => json_encode([2]),
                'faculty_id' => null,
                'schedule' => 'TTH 3:00-4:30 PM',
                'room' => 'Lab 202',
                'max_students' => 35,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        // Insert Guardians IT
        DB::table('guardians_it')->insert([
            [
                'fname' => 'Roberto',
                'mname' => 'Santos',
                'lname' => 'Reyes',
                'relationship' => 'Father',
                'contact_num' => '09123456789',
                'address' => '123 Main St, City',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'fname' => 'Maria',
                'mname' => 'Garcia',
                'lname' => 'Santos',
                'relationship' => 'Mother',
                'contact_num' => '09123456790',
                'address' => '456 Oak Ave, City',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        // Insert Guardians CS
        DB::table('guardians_cs')->insert([
            [
                'fname' => 'Juan',
                'mname' => 'Dela Cruz',
                'lname' => 'Perez',
                'relationship' => 'Father',
                'contact_num' => '09123456791',
                'address' => '789 Pine St, City',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'fname' => 'Elena',
                'mname' => 'Reyes',
                'lname' => 'Gonzalez',
                'relationship' => 'Mother',
                'contact_num' => '09123456792',
                'address' => '321 Elm St, City',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        // Update existing students with new fields
        DB::table('students')->where('stud_num', '2021001')->update([
            'mname' => 'Doe',
            'ext' => null,
            'contact_num' => '09123456789',
            'address' => '123 Main St, City',
            'guardian_table' => 'guardians_it',
            'guardian' => 1,
            'program_code' => 'BSIT',
            'courses' => json_encode([1]),
            'standing' => '3rd',
            'academic_status' => 'Regular',
        ]);

        DB::table('students')->where('stud_num', '2021002')->update([
            'mname' => 'Smith',
            'ext' => null,
            'contact_num' => '09123456790',
            'address' => '456 Oak Ave, City',
            'guardian_table' => 'guardians_it',
            'guardian' => 2,
            'program_code' => 'BSIT',
            'courses' => json_encode([2]),
            'standing' => '3rd',
            'academic_status' => 'Regular',
        ]);
    }
}
