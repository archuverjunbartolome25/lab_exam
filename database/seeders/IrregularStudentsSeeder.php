<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Student;
use App\Models\StudentAffiliation;
use App\Models\StudentSkill;
use App\Models\StudentViolation;
use App\Models\StudentAchievement;
use App\Models\AcademicHistory;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class IrregularStudentsSeeder extends Seeder
{
    public function run(): void
    {
        // Create irregular students with diverse academic situations
        $irregularStudentsData = [
            [
                'stud_num' => '2022-025',
                'fname' => 'Daniel',
                'lname' => 'Martinez',
                'gender' => 'Male',
                'bday' => '2001-06-15',
                'email' => 'daniel.martinez@university.edu',
                'contact_num' => '09123456999',
                'address' => '789 Extended Street, Quezon City',
                'program' => 'BSIT',
                'program_code' => 'BSIT',
                'standing' => '3rd Year',
                'academic_status' => 'Irregular',
                'courses' => json_encode([1, 3, 5, 7]), // Irregular course pattern
            ],
            [
                'stud_num' => '2021-030',
                'fname' => 'Patricia',
                'mname' => 'Anne',
                'lname' => 'Reyes',
                'gender' => 'Female',
                'bday' => '2001-09-20',
                'email' => 'patricia.reyes@university.edu',
                'contact_num' => '09123456998',
                'address' => '456 Delayed Road, Manila',
                'program' => 'BSCS',
                'program_code' => 'BSCS',
                'standing' => '4th Year',
                'academic_status' => 'Irregular',
                'courses' => json_encode([2, 4, 6, 8]), // Irregular course pattern
            ],
            [
                'stud_num' => '2023-026',
                'fname' => 'Carlos',
                'lname' => 'Santos',
                'gender' => 'Male',
                'bday' => '2003-02-28',
                'email' => 'carlos.santos@university.edu',
                'contact_num' => '09123456997',
                'address' => '123 Probation Avenue, Makati',
                'program' => 'BSIT',
                'program_code' => 'BSIT',
                'standing' => '2nd Year',
                'academic_status' => 'Irregular',
                'courses' => json_encode([1, 9, 10]), // Irregular course pattern
            ],
            [
                'stud_num' => '2020-035',
                'fname' => 'Michelle',
                'lname' => 'Garcia',
                'gender' => 'Female',
                'bday' => '2000-12-10',
                'email' => 'michelle.garcia@university.edu',
                'contact_num' => '09123456996',
                'address' => '789 Extended Stay, Cebu',
                'program' => 'BSCS',
                'program_code' => 'BSCS',
                'standing' => '5th Year',
                'academic_status' => 'Irregular',
                'courses' => json_encode([11, 12, 13]), // Extended student
            ],
        ];

        // Insert irregular students
        foreach ($irregularStudentsData as $studentData) {
            $student = Student::create($studentData);
            
            // Create profiles for irregular students (often with challenges)
            $this->createIrregularStudentProfile($student);
        }
    }

    private function createIrregularStudentProfile($student)
    {
        // Irregular students often have different patterns
        
        // Fewer affiliations due to time constraints
        $this->createLimitedAffiliations($student);
        
        // Skills might be more focused or delayed
        $this->createSkills($student);
        
        // Higher chance of violations due to academic stress
        if (rand(1, 10) <= 6) { // 60% chance
            $this->createViolations($student);
        }
        
        // Fewer achievements but some remarkable ones
        $this->createAchievements($student);
        
        // Academic history with gaps and challenges
        $this->createAcademicHistory($student);
    }

    private function createLimitedAffiliations($student)
    {
        // Irregular students have fewer affiliations due to time constraints
        $affiliationOptions = [
            ['Part-time Job Support Group', 'organization', 'Member'],
            ['Academic Counseling', 'organization', 'Participant'],
            ['Peer Tutoring', 'club', 'Tutor'],
            ['Career Guidance', 'organization', 'Member'],
        ];

        $numAffiliations = rand(0, 2); // 0-2 affiliations
        if ($numAffiliations > 0) {
            $affiliationKeys = array_rand($affiliationOptions, $numAffiliations);
            
            if (!is_array($affiliationKeys)) {
                $affiliationKeys = [$affiliationKeys];
            }

            foreach ($affiliationKeys as $key) {
                $affiliation = $affiliationOptions[$key];
                StudentAffiliation::create([
                    'stud_id' => $student->stud_id,
                    'type' => $affiliation[1],
                    'name' => $affiliation[0],
                    'role' => $affiliation[2],
                    'start_date' => Carbon::now()->subMonths(rand(3, 12)),
                    'end_date' => null,
                    'description' => "Limited participation in {$affiliation[0]} due to academic constraints",
                ]);
            }
        }
    }

    private function createSkills($student)
    {
        $skillOptions = [
            ['technical', 'Time Management', 'intermediate'],
            ['technical', 'Independent Learning', 'advanced'],
            ['technical', 'Basic Programming', 'beginner'],
            ['soft', 'Resilience', 'advanced'],
            ['soft', 'Problem Solving', 'intermediate'],
            ['language', 'English', 'intermediate'],
        ];

        $numSkills = rand(2, 4); // Fewer skills
        $skillKeys = array_rand($skillOptions, $numSkills);

        if (!is_array($skillKeys)) {
            $skillKeys = [$skillKeys];
        }

        foreach ($skillKeys as $key) {
            $skill = $skillOptions[$key];
            StudentSkill::create([
                'stud_id' => $student->stud_id,
                'category' => $skill[0],
                'name' => $skill[1],
                'level' => $skill[2],
                'description' => "Developed {$skill[1]} through challenging academic journey",
                'acquired_date' => Carbon::now()->subMonths(rand(6, 24)),
                'certificate' => null, // Fewer certifications
            ]);
        }
    }

    private function createViolations($student)
    {
        $violationOptions = [
            ['academic', 'major', 'Failed to meet academic requirements'],
            ['academic', 'minor', 'Late thesis submission'],
            ['attendance', 'major', 'Excessive absences due to work'],
            ['disciplinary', 'minor', 'Academic probation violation'],
            ['academic', 'critical', 'Academic dismissal warning'],
        ];

        $numViolations = rand(1, 3); // More violations
        $violationKeys = array_rand($violationOptions, $numViolations);

        if (!is_array($violationKeys)) {
            $violationKeys = [$violationKeys];
        }

        foreach ($violationKeys as $key) {
            $violation = $violationOptions[$key];
            StudentViolation::create([
                'stud_id' => $student->stud_id,
                'type' => $violation[0],
                'severity' => $violation[1],
                'description' => $violation[2],
                'violation_date' => Carbon::now()->subDays(rand(1, 365)),
                'resolved_date' => $violation[1] === 'minor' ? Carbon::now()->subDays(rand(1, 60)) : null,
                'status' => $violation[1] === 'minor' ? 'resolved' : 'pending',
                'sanction' => $violation[1] === 'minor' ? 'Warning' : ($violation[1] === 'major' ? 'Academic Probation' : 'Academic Warning'),
            ]);
        }
    }

    private function createAchievements($student)
    {
        // Fewer but meaningful achievements
        $achievementOptions = [
            ['academic', 'Academic Recovery', 'Successfully improved grades after probation'],
            ['extracurricular', 'Perseverance Award', 'Recognized for academic resilience'],
            ['academic', 'Course Completion', 'Completed challenging remedial courses'],
            ['extracurricular', 'Peer Support', 'Helped other struggling students'],
        ];

        $numAchievements = rand(0, 2); // Fewer achievements
        if ($numAchievements > 0) {
            $achievementKeys = array_rand($achievementOptions, $numAchievements);
            
            if (!is_array($achievementKeys)) {
                $achievementKeys = [$achievementKeys];
            }

            foreach ($achievementKeys as $key) {
                $achievement = $achievementOptions[$key];
                StudentAchievement::create([
                    'stud_id' => $student->stud_id,
                    'type' => $achievement[0],
                    'title' => $achievement[1],
                    'description' => $achievement[2],
                    'achievement_date' => Carbon::now()->subDays(rand(1, 365)),
                    'level' => 'school',
                    'awarder' => 'Academic Counseling Office',
                ]);
            }
        }
    }

    private function createAcademicHistory($student)
    {
        // Academic history with gaps and challenges
        $courseOptions = $student->program === 'BSIT' ? [
            ['IT101', 'Introduction to Information Technology', 3.0],
            ['IT102', 'Computer Programming 1', 3.0],
            ['IT103', 'Web Development', 3.0],
            ['IT104', 'Database Management', 3.0],
            ['IT105', 'Remedial Mathematics', 2.0],
            ['IT106', 'Academic Support Course', 1.0],
        ] : [
            ['CS101', 'Introduction to Computer Science', 4.0],
            ['CS102', 'Programming Fundamentals', 4.0],
            ['CS103', 'Remedial Programming', 3.0],
            ['CS104', 'Computer Architecture', 3.0],
            ['CS105', 'Academic Recovery Course', 2.0],
        ];

        $semesters = ['First Semester', 'Second Semester'];
        $statuses = ['completed', 'completed', 'failed', 'dropped', 'in_progress'];

        // Create irregular academic pattern
        $totalCourses = rand(8, 12);
        $currentYear = 2020;
        $currentSemester = 0;

        for ($i = 0; $i < $totalCourses; $i++) {
            // Skip some semesters to create gaps
            if (rand(1, 10) <= 3) { // 30% chance of gap
                $currentSemester++;
                if ($currentSemester >= 2) {
                    $currentYear++;
                    $currentSemester = 0;
                }
                continue;
            }

            $courseKey = array_rand($courseOptions);
            $course = $courseOptions[$courseKey];
            
            // Lower grades for irregular students
            $grade = rand(15, 30) / 10; // 1.5 to 3.0 range
            if (rand(1, 10) <= 4) { // 40% chance of failing
                $grade = rand(10, 17) / 10; // 1.0 to 1.7 range
            }

            AcademicHistory::create([
                'stud_id' => $student->stud_id,
                'course_id' => null,
                'course_code' => $course[0],
                'course_name' => $course[1],
                'semester' => $semesters[$currentSemester],
                'year' => $currentYear,
                'grade' => $i < $totalCourses - 2 ? $grade : null,
                'credits' => $course[2],
                'grade_letter' => $this->getGradeLetter($grade),
                'status' => $i < $totalCourses - 2 ? $statuses[rand(0, 3)] : 'in_progress',
            ]);

            $currentSemester++;
            if ($currentSemester >= 2) {
                $currentYear++;
                $currentSemester = 0;
            }
        }
    }

    private function getGradeLetter($grade)
    {
        if ($grade >= 3.7) return 'A';
        if ($grade >= 3.3) return 'B+';
        if ($grade >= 3.0) return 'B';
        if ($grade >= 2.7) return 'C+';
        if ($grade >= 2.3) return 'C';
        if ($grade >= 2.0) return 'D+';
        if ($grade >= 1.7) return 'D';
        return 'F';
    }
}
