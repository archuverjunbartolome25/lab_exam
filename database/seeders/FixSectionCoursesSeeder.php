<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class FixSectionCoursesSeeder extends Seeder
{
    public function run(): void
    {
        // Update section 4IT-E to use existing course IDs 1 and 2
        DB::table('sections_it')
            ->where('section', '4IT-E')
            ->update([
                'courses' => json_encode([1, 2]), // Use existing course IDs
                'updated_at' => Carbon::now(),
            ]);
    }
}
