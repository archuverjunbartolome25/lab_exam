<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class CoursesSeeder extends Seeder
{
    public function run(): void
    {
        // Insert Courses IT (starting from ID 7 to avoid conflicts)
        DB::table('courses_it')->insert([
            [
                'course_id' => 7,
                'course' => 'Introduction to Computing',
                'course_code' => 'CCS101',
                'credits' => 3,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'course_id' => 8,
                'course' => 'Programming Fundamentals',
                'course_code' => 'CCS102',
                'credits' => 3,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'course_id' => 9,
                'course' => 'Web Development',
                'course_code' => 'CCS103',
                'credits' => 3,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'course_id' => 10,
                'course' => 'Database Systems',
                'course_code' => 'CCS104',
                'credits' => 3,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'course_id' => 11,
                'course' => 'Data Structures',
                'course_code' => 'CCS105',
                'credits' => 3,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'course_id' => 12,
                'course' => 'Software Engineering',
                'course_code' => 'CCS106',
                'credits' => 3,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ]);

        // Insert Courses CS
        DB::table('courses_cs')->insert([
            [
                'course_id' => 1,
                'course' => 'Computer Science Fundamentals',
                'course_code' => 'CCS101',
                'credits' => 3,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'course_id' => 2,
                'course' => 'Algorithms and Complexity',
                'course_code' => 'CCS102',
                'credits' => 3,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'course_id' => 3,
                'course' => 'Operating Systems',
                'course_code' => 'CCS103',
                'credits' => 3,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'course_id' => 4,
                'course' => 'Computer Networks',
                'course_code' => 'CCS104',
                'credits' => 3,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'course_id' => 5,
                'course' => 'Artificial Intelligence',
                'course_code' => 'CCS105',
                'credits' => 3,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'course_id' => 6,
                'course' => 'Machine Learning',
                'course_code' => 'CCS106',
                'credits' => 3,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ]);
    }
}
