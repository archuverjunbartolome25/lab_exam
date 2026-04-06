<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class SectionsSeeder extends Seeder
{
    public function run(): void
    {
        // Insert Sections IT (starting from ID 10 to avoid conflicts)
        DB::table('sections_it')->insert([
            [
                'section_id' => 10,
                'section' => '1IT-A',
                'courses' => json_encode([7, 8]), // Use correct course IDs
                'num_of_stud' => 25,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'section_id' => 11,
                'section' => '1IT-B',
                'courses' => json_encode([9, 10]), // Use correct course IDs
                'num_of_stud' => 20,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'section_id' => 12,
                'section' => '2IT-A',
                'courses' => json_encode([11, 12]), // Use correct course IDs
                'num_of_stud' => 30,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'section_id' => 100,
                'section' => '4IT-E',
                'courses' => json_encode([7, 8]), // Use correct course IDs
                'num_of_stud' => 50,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ]);

        // Insert Sections CS (use course IDs 1-6 since CS courses use lower IDs)
        DB::table('sections_cs')->insert([
            [
                'section_id' => 10,
                'section' => '1CS-A',
                'courses' => json_encode([1, 2]), // CCS101, CCS102
                'num_of_stud' => 28,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'section_id' => 11,
                'section' => '1CS-B',
                'courses' => json_encode([3, 4]), // CCS103, CCS104
                'num_of_stud' => 22,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'section_id' => 12,
                'section' => '2CS-A',
                'courses' => json_encode([5, 6]), // CCS105, CCS106
                'num_of_stud' => 26,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ]);
    }
}
