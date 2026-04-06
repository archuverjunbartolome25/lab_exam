<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Student;
use App\Models\SectionsIT;
use App\Models\SectionsCS;
use Illuminate\Support\Facades\DB;

class AssignSectionIdToStudentsSeeder extends Seeder
{
    public function run(): void
    {
        // Clear existing section_id assignments
        Student::whereNotNull('section_id')->update(['section_id' => null]);
        
        // Get all students
        $students = Student::all();
        
        // Section assignments by year and program
        $sectionAssignments = [
            'IT' => [
                '1st Year' => ['1IT-A', '1IT-B', '1IT-C', '1IT-D', '1IT-E'],
                '2nd Year' => ['2IT-A', '2IT-B', '2IT-C', '2IT-D', '2IT-E'],
                '3rd Year' => ['3IT-A', '3IT-B', '3IT-C', '3IT-D', '3IT-E'],
                '4th Year' => ['4IT-A', '4IT-B', '4IT-C', '4IT-D', '4IT-E'],
                'Irregular' => ['4IT-A', '4IT-B', '4IT-C'],
            ],
            'CS' => [
                '1st Year' => ['1CS-A', '1CS-B', '1CS-C', '1CS-D', '1CS-E'],
                '2nd Year' => ['2CS-A', '2CS-B', '2CS-C', '2CS-D', '2CS-E'],
                '3rd Year' => ['3CS-A', '3CS-B', '3CS-C', '3CS-D', '3CS-E'],
                '4th Year' => ['4CS-A', '4CS-B', '4CS-C', '4CS-D', '4CS-E'],
                'Irregular' => ['4CS-A', '4CS-B', '4CS-C'],
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
            
            // Get the section ID based on program type
            $sectionId = null;
            if ($programType === 'IT') {
                $section = SectionsIT::where('section', $assignedSection)->first();
                if ($section) {
                    $sectionId = $section->section_id;
                }
            } else {
                $section = SectionsCS::where('section', $assignedSection)->first();
                if ($section) {
                    $sectionId = $section->section_id;
                }
            }
            
            // Update the student's section_id
            if ($sectionId) {
                $student->section_id = $sectionId;
                $student->save();
                $assignedCount++;
                
                $this->command->info("Assigned {$student->fname} {$student->lname} ({$student->program}) to {$assignedSection} (ID: {$sectionId})");
            }
        }
        
        $this->command->info("Successfully assigned section_id to {$assignedCount} students");
        
        // Show student distribution
        $this->command->info("\nStudent Section Assignments:");
        
        $studentsWithSections = Student::with(['sectionIT', 'sectionCS'])->get();
        foreach ($studentsWithSections as $student) {
            $sectionName = 'None';
            if ($student->program === 'BSIT' && $student->sectionIT) {
                $sectionName = $student->sectionIT->section;
            } elseif ($student->program === 'BSCS' && $student->sectionCS) {
                $sectionName = $student->sectionCS->section;
            }
            
            $this->command->info("{$student->fname} {$student->lname} ({$student->program}): {$sectionName}");
        }
    }
}
