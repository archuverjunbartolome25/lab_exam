<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class UpdateSectionSeeder extends Seeder
{
    public function run(): void
    {
        // Update existing 4IT-E section to use correct course IDs
        DB::table('sections_it')
            ->where('section', '4IT-E')
            ->update([
                'courses' => json_encode([7, 8]), // Use correct course IDs
                'updated_at' => Carbon::now(),
            ]);
    }
}
