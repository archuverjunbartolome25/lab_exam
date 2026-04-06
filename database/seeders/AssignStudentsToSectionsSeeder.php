<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Student;
use App\Models\SectionsIT;
use App\Models\SectionsCS;
use Illuminate\Support\Facades\DB;

class AssignStudentsToSectionsSeeder extends Seeder
{
    public function run(): void
    {
        // Get all students
        $students = Student::all();
        
        // Get all sections
        $itSections = SectionsIT::all()->keyBy('section');
        $csSections = SectionsCS::all()->keyBy('section');
        
        // Course assignments by year and program
        $courseAssignments = [
            // IT courses by year
            'IT' => [
                '1st Year' => [1, 2, 3],
                '2nd Year' => [4, 5, 6],
                '3rd Year' => [7, 8, 9],
                '4th Year' => [10, 11, 12],
                'Irregular' => [1, 4, 7, 10], // Mix of courses for irregular
            ],
            // CS courses by year
            'CS' => [
                '1st Year' => [13, 14, 15],
                '2nd Year' => [16, 17, 18],
                '3rd Year' => [19, 20, 21],
                '4th Year' => [22, 23, 24],
                'Irregular' => [13, 16, 19, 22], // Mix of courses for irregular
            ]
        ];
        
        // Section assignments by year and program
        $sectionAssignments = [
            'IT' => [
                '1st Year' => ['1IT-A', '1IT-B', '1IT-C', '1IT-D', '1IT-E'],
                '2nd Year' => ['2IT-A', '2IT-B', '2IT-C', '2IT-D', '2IT-E'],
                '3rd Year' => ['3IT-A', '3IT-B', '3IT-C', '3IT-D', '3IT-E'],
                '4th Year' => ['4IT-A', '4IT-B', '4IT-C', '4IT-D', '4IT-E'],
                'Irregular' => ['4IT-A', '4IT-B', '4IT-C'], // Some irregular in 4th year sections
            ],
            'CS' => [
                '1st Year' => ['1CS-A', '1CS-B', '1CS-C', '1CS-D', '1CS-E'],
                '2nd Year' => ['2CS-A', '2CS-B', '2CS-C', '2CS-D', '2CS-E'],
                '3rd Year' => ['3CS-A', '3CS-B', '3CS-C', '3CS-D', '3CS-E'],
                '4th Year' => ['4CS-A', '4CS-B', '4CS-C', '4CS-D', '4CS-E'],
                'Irregular' => ['4CS-A', '4CS-B', '4CS-C'], // Some irregular in 4th year sections
            ]
        ];
        
        $assignedCount = 0;
        
        foreach ($students as $student) {
            // Determine program type
            $programType = $student->program === 'BSIT' ? 'IT' : 'CS';
            
            // Get student's year level
            $yearLevel = $student->standing;
            
            // Handle irregular students
            if ($student->academic_status === 'Irregular') {
                $yearLevel = 'Irregular';
            }
            
            // Get available sections for this year/program
            $availableSections = $sectionAssignments[$programType][$yearLevel] ?? [];
            
            if (empty($availableSections)) {
                // Fallback to 1st year if no matching sections
                $availableSections = $sectionAssignments[$programType]['1st Year'];
                $yearLevel = '1st Year';
            }
            
            // Assign student to a random section from available options
            $assignedSection = $availableSections[array_rand($availableSections)];
            
            // Get courses for this year/program
            $courses = $courseAssignments[$programType][$yearLevel] ?? $courseAssignments[$programType]['1st Year'];
            
            // Update student's courses and section
            $student->courses = json_encode($courses);
            $student->standing = $yearLevel;
            $student->save();
            
            $assignedCount++;
            
            $this->command->info("Assigned {$student->fname} {$student->lname} ({$student->program}) to {$assignedSection} with courses: " . implode(', ', $courses));
        }
        
        $this->command->info("Successfully assigned {$assignedCount} students to sections");
        
        // Show section distribution
        $this->command->info("\nSection Distribution:");
        
        // IT sections
        $this->command->info("\nIT Sections:");
        foreach ($itSections as $section) {
            $studentCount = Student::where('program', 'BSIT')
                ->whereJsonContains('courses', $section->courses)
                ->count();
            $this->command->info("{$section->section}: {$studentCount} students");
        }
        
        // CS sections
        $this->command->info("\nCS Sections:");
        foreach ($csSections as $section) {
            $studentCount = Student::where('program', 'BSCS')
                ->whereJsonContains('courses', $section->courses)
                ->count();
            $this->command->info("{$section->section}: {$studentCount} students");
        }
    }
}
