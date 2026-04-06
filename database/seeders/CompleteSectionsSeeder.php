<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\SectionsIT;
use App\Models\SectionsCS;
use Illuminate\Support\Facades\DB;

class CompleteSectionsSeeder extends Seeder
{
    public function run(): void
    {
        // Clear existing sections
        SectionsIT::truncate();
        SectionsCS::truncate();
        
        // Create IT sections (1IT-A through 4IT-E)
        $itSections = [
            // 1st Year IT Sections
            ['section' => '1IT-A', 'courses' => json_encode([1, 2, 3])],
            ['section' => '1IT-B', 'courses' => json_encode([1, 2, 3])],
            ['section' => '1IT-C', 'courses' => json_encode([1, 2, 3])],
            ['section' => '1IT-D', 'courses' => json_encode([1, 2, 3])],
            ['section' => '1IT-E', 'courses' => json_encode([1, 2, 3])],
            
            // 2nd Year IT Sections
            ['section' => '2IT-A', 'courses' => json_encode([4, 5, 6])],
            ['section' => '2IT-B', 'courses' => json_encode([4, 5, 6])],
            ['section' => '2IT-C', 'courses' => json_encode([4, 5, 6])],
            ['section' => '2IT-D', 'courses' => json_encode([4, 5, 6])],
            ['section' => '2IT-E', 'courses' => json_encode([4, 5, 6])],
            
            // 3rd Year IT Sections
            ['section' => '3IT-A', 'courses' => json_encode([7, 8, 9])],
            ['section' => '3IT-B', 'courses' => json_encode([7, 8, 9])],
            ['section' => '3IT-C', 'courses' => json_encode([7, 8, 9])],
            ['section' => '3IT-D', 'courses' => json_encode([7, 8, 9])],
            ['section' => '3IT-E', 'courses' => json_encode([7, 8, 9])],
            
            // 4th Year IT Sections
            ['section' => '4IT-A', 'courses' => json_encode([10, 11, 12])],
            ['section' => '4IT-B', 'courses' => json_encode([10, 11, 12])],
            ['section' => '4IT-C', 'courses' => json_encode([10, 11, 12])],
            ['section' => '4IT-D', 'courses' => json_encode([10, 11, 12])],
            ['section' => '4IT-E', 'courses' => json_encode([10, 11, 12])],
        ];
        
        // Create CS sections (1CS-A through 4CS-E)
        $csSections = [
            // 1st Year CS Sections
            ['section' => '1CS-A', 'courses' => json_encode([13, 14, 15])],
            ['section' => '1CS-B', 'courses' => json_encode([13, 14, 15])],
            ['section' => '1CS-C', 'courses' => json_encode([13, 14, 15])],
            ['section' => '1CS-D', 'courses' => json_encode([13, 14, 15])],
            ['section' => '1CS-E', 'courses' => json_encode([13, 14, 15])],
            
            // 2nd Year CS Sections
            ['section' => '2CS-A', 'courses' => json_encode([16, 17, 18])],
            ['section' => '2CS-B', 'courses' => json_encode([16, 17, 18])],
            ['section' => '2CS-C', 'courses' => json_encode([16, 17, 18])],
            ['section' => '2CS-D', 'courses' => json_encode([16, 17, 18])],
            ['section' => '2CS-E', 'courses' => json_encode([16, 17, 18])],
            
            // 3rd Year CS Sections
            ['section' => '3CS-A', 'courses' => json_encode([19, 20, 21])],
            ['section' => '3CS-B', 'courses' => json_encode([19, 20, 21])],
            ['section' => '3CS-C', 'courses' => json_encode([19, 20, 21])],
            ['section' => '3CS-D', 'courses' => json_encode([19, 20, 21])],
            ['section' => '3CS-E', 'courses' => json_encode([19, 20, 21])],
            
            // 4th Year CS Sections
            ['section' => '4CS-A', 'courses' => json_encode([22, 23, 24])],
            ['section' => '4CS-B', 'courses' => json_encode([22, 23, 24])],
            ['section' => '4CS-C', 'courses' => json_encode([22, 23, 24])],
            ['section' => '4CS-D', 'courses' => json_encode([22, 23, 24])],
            ['section' => '4CS-E', 'courses' => json_encode([22, 23, 24])],
        ];
        
        // Insert IT sections
        foreach ($itSections as $section) {
            SectionsIT::create($section);
        }
        
        // Insert CS sections
        foreach ($csSections as $section) {
            SectionsCS::create($section);
        }
        
        $this->command->info('Created ' . count($itSections) . ' IT sections and ' . count($csSections) . ' CS sections');
        $this->command->info('Total sections: ' . (count($itSections) + count($csSections)));
    }
}
