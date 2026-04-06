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

class AdditionalStudentsSeeder extends Seeder
{
    public function run(): void
    {
        // Create additional students with diverse profiles
        $studentsData = [
            [
                'stud_num' => '2023-015',
                'fname' => 'Maria',
                'mname' => 'Isabella',
                'lname' => 'Santos',
                'gender' => 'Female',
                'bday' => '2002-05-15',
                'email' => 'maria.santos@university.edu',
                'contact_num' => '09123456789',
                'address' => '123 Manila Street, Quezon City',
                'program' => 'BSIT',
                'program_code' => 'BSIT',
                'standing' => '3rd Year',
                'academic_status' => 'Regular',
                'courses' => json_encode([1, 2, 3, 4]),
            ],
            [
                'stud_num' => '2023-016',
                'fname' => 'John',
                'lname' => 'Reyes',
                'gender' => 'Male',
                'bday' => '2003-08-22',
                'email' => 'john.reyes@university.edu',
                'contact_num' => '09123456790',
                'address' => '456 Makati Avenue, Makati City',
                'program' => 'BSCS',
                'program_code' => 'BSCS',
                'standing' => '2nd Year',
                'academic_status' => 'Regular',
                'courses' => json_encode([5, 6, 7]),
            ],
            [
                'stud_num' => '2023-017',
                'fname' => 'Sarah',
                'mname' => 'Grace',
                'lname' => 'Lim',
                'gender' => 'Female',
                'bday' => '2002-11-30',
                'email' => 'sarah.lim@university.edu',
                'contact_num' => '09123456791',
                'address' => '789 Cebu Road, Cebu City',
                'program' => 'BSIT',
                'program_code' => 'BSIT',
                'standing' => '4th Year',
                'academic_status' => 'Regular',
                'courses' => json_encode([8, 9, 10]),
            ],
            [
                'stud_num' => '2023-018',
                'fname' => 'Michael',
                'lname' => 'Tan',
                'gender' => 'Male',
                'bday' => '2003-03-18',
                'email' => 'michael.tan@university.edu',
                'contact_num' => '09123456792',
                'address' => '321 Davao Street, Davao City',
                'program' => 'BSCS',
                'program_code' => 'BSCS',
                'standing' => '1st Year',
                'academic_status' => 'Regular',
                'courses' => json_encode([11, 12]),
            ],
            [
                'stud_num' => '2023-019',
                'fname' => 'Emily',
                'lname' => 'Cruz',
                'gender' => 'Female',
                'bday' => '2002-07-25',
                'email' => 'emily.cruz@university.edu',
                'contact_num' => '09123456793',
                'address' => '654 Iloilo Avenue, Iloilo City',
                'program' => 'BSIT',
                'program_code' => 'BSIT',
                'standing' => '3rd Year',
                'academic_status' => 'Regular',
                'courses' => json_encode([13, 14, 15]),
            ],
            [
                'stud_num' => '2023-020',
                'fname' => 'David',
                'mname' => 'James',
                'lname' => 'Garcia',
                'gender' => 'Male',
                'bday' => '2003-01-10',
                'email' => 'david.garcia@university.edu',
                'contact_num' => '09123456794',
                'address' => '987 Baguio Road, Baguio City',
                'program' => 'BSCS',
                'program_code' => 'BSCS',
                'standing' => '2nd Year',
                'academic_status' => 'Regular',
                'courses' => json_encode([16, 17, 18]),
            ],
            [
                'stud_num' => '2023-021',
                'fname' => 'Lisa',
                'lname' => 'Fernandez',
                'gender' => 'Female',
                'bday' => '2002-09-14',
                'email' => 'lisa.fernandez@university.edu',
                'contact_num' => '09123456795',
                'address' => '147 Palawan Street, Puerto Princesa',
                'program' => 'BSIT',
                'program_code' => 'BSIT',
                'standing' => '4th Year',
                'academic_status' => 'Regular',
                'courses' => json_encode([19, 20, 21]),
            ],
            [
                'stud_num' => '2023-022',
                'fname' => 'Robert',
                'lname' => 'Mendoza',
                'gender' => 'Male',
                'bday' => '2003-06-28',
                'email' => 'robert.mendoza@university.edu',
                'contact_num' => '09123456796',
                'address' => '258 Batangas Road, Batangas City',
                'program' => 'BSCS',
                'program_code' => 'BSCS',
                'standing' => '3rd Year',
                'academic_status' => 'Regular',
                'courses' => json_encode([22, 23, 24]),
            ],
            [
                'stud_num' => '2023-023',
                'fname' => 'Jennifer',
                'mname' => 'Rose',
                'lname' => 'Ramos',
                'gender' => 'Female',
                'bday' => '2002-12-05',
                'email' => 'jennifer.ramos@university.edu',
                'contact_num' => '09123456797',
                'address' => '369 Laguna Street, Laguna',
                'program' => 'BSIT',
                'program_code' => 'BSIT',
                'standing' => '2nd Year',
                'academic_status' => 'Regular',
                'courses' => json_encode([25, 26]),
            ],
            [
                'stud_num' => '2023-024',
                'fname' => 'Christopher',
                'lname' => 'Castillo',
                'gender' => 'Male',
                'bday' => '2003-04-12',
                'email' => 'christopher.castillo@university.edu',
                'contact_num' => '09123456798',
                'address' => '741 Pampanga Avenue, Pampanga',
                'program' => 'BSCS',
                'program_code' => 'BSCS',
                'standing' => '4th Year',
                'academic_status' => 'Regular',
                'courses' => json_encode([27, 28, 29]),
            ],
        ];

        // Insert students
        foreach ($studentsData as $studentData) {
            $student = Student::create($studentData);
            
            // Create diverse profiles for each student
            $this->createStudentProfile($student);
        }
    }

    private function createStudentProfile($student)
    {
        // Create affiliations based on student characteristics
        $this->createAffiliations($student);
        
        // Create skills based on program and interests
        $this->createSkills($student);
        
        // Create violations for some students
        if (rand(1, 10) <= 4) { // 40% chance
            $this->createViolations($student);
        }
        
        // Create achievements
        $this->createAchievements($student);
        
        // Create academic history with realistic grades
        $this->createAcademicHistory($student);
    }

    private function createAffiliations($student)
    {
        $affiliationOptions = [
            'BSIT' => [
                ['Programming Club', 'club', 'Member'],
                ['Web Development Team', 'team', 'Developer'],
                ['IT Society', 'organization', 'Member'],
                ['Cybersecurity Club', 'club', 'Member'],
                ['Student Council', 'organization', 'IT Representative'],
            ],
            'BSCS' => [
                ['Computer Science Society', 'organization', 'Member'],
                ['AI Research Group', 'team', 'Research Assistant'],
                ['Algorithm Club', 'club', 'Member'],
                ['Robotics Team', 'team', 'Programmer'],
                ['Academic Honor Society', 'organization', 'Member'],
            ]
        ];

        $programAffiliations = $affiliationOptions[$student->program] ?? $affiliationOptions['BSIT'];
        $numAffiliations = rand(1, 3);
        $selectedAffiliations = array_rand($programAffiliations, $numAffiliations);

        if (!is_array($selectedAffiliations)) {
            $selectedAffiliations = [$selectedAffiliations];
        }

        foreach ($selectedAffiliations as $key) {
            $affiliation = $programAffiliations[$key];
            StudentAffiliation::create([
                'stud_id' => $student->stud_id,
                'type' => $affiliation[1],
                'name' => $affiliation[0],
                'role' => $affiliation[2],
                'start_date' => Carbon::now()->subMonths(rand(6, 36)),
                'end_date' => rand(0, 1) ? null : Carbon::now()->addMonths(rand(1, 12)),
                'description' => "Active participation in {$affiliation[0]}",
            ]);
        }
    }

    private function createSkills($student)
    {
        $skillOptions = [
            'BSIT' => [
                ['technical', 'JavaScript', 'advanced'],
                ['technical', 'React', 'intermediate'],
                ['technical', 'Node.js', 'intermediate'],
                ['technical', 'Database Design', 'advanced'],
                ['technical', 'Network Administration', 'intermediate'],
                ['soft', 'Project Management', 'intermediate'],
                ['soft', 'Client Communication', 'advanced'],
                ['language', 'English', 'advanced'],
            ],
            'BSCS' => [
                ['technical', 'Python', 'expert'],
                ['technical', 'Machine Learning', 'advanced'],
                ['technical', 'Data Structures', 'expert'],
                ['technical', 'Algorithms', 'advanced'],
                ['technical', 'Java', 'advanced'],
                ['soft', 'Critical Thinking', 'expert'],
                ['soft', 'Problem Solving', 'expert'],
                ['language', 'English', 'advanced'],
            ]
        ];

        $programSkills = $skillOptions[$student->program] ?? $skillOptions['BSIT'];
        $numSkills = rand(4, 7);
        $skillKeys = array_rand($programSkills, $numSkills);

        if (!is_array($skillKeys)) {
            $skillKeys = [$skillKeys];
        }

        foreach ($skillKeys as $key) {
            $skill = $programSkills[$key];
            StudentSkill::create([
                'stud_id' => $student->stud_id,
                'category' => $skill[0],
                'name' => $skill[1],
                'level' => $skill[2],
                'description' => "Proficient in {$skill[1]} at {$skill[2]} level",
                'acquired_date' => Carbon::now()->subMonths(rand(1, 48)),
                'certificate' => rand(0, 1) ? "{$skill[1]} Certification" : null,
            ]);
        }
    }

    private function createViolations($student)
    {
        $violationOptions = [
            ['academic', 'minor', 'Late assignment submission'],
            ['disciplinary', 'minor', 'Unauthorized absence'],
            ['attendance', 'major', 'Excessive tardiness'],
            ['academic', 'major', 'Plagiarism detected'],
            ['disciplinary', 'critical', 'Fighting on campus'],
            ['attendance', 'minor', 'Skipping class'],
            ['academic', 'minor', 'Cheating on quiz'],
        ];

        $numViolations = rand(1, 2);
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
                'violation_date' => Carbon::now()->subDays(rand(1, 180)),
                'resolved_date' => $violation[1] === 'minor' ? Carbon::now()->subDays(rand(1, 30)) : null,
                'status' => $violation[1] === 'minor' ? 'resolved' : 'pending',
                'sanction' => $violation[1] === 'minor' ? 'Warning' : ($violation[1] === 'major' ? 'Probation' : 'Suspension'),
            ]);
        }
    }

    private function createAchievements($student)
    {
        $achievementOptions = [
            ['academic', 'Dean\'s List', 'Outstanding academic performance for the semester'],
            ['academic', 'Honor Roll', 'Consistent high grades throughout the year'],
            ['academic', 'Research Excellence', 'Published research paper in student journal'],
            ['athletic', 'Basketball MVP', 'Most valuable player in inter-college tournament'],
            ['athletic', 'Chess Champion', 'Won university chess competition'],
            ['extracurricular', 'Best Speaker', 'Won debate competition'],
            ['extracurricular', 'Leadership Award', 'Outstanding leadership in student council'],
            ['extracurricular', 'Community Service', '100+ hours of community service'],
            ['technical', 'Hackathon Winner', 'First place in university hackathon'],
            ['technical', 'Coding Competition', 'Top performer in programming contest'],
        ];

        $numAchievements = rand(2, 5);
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
                'achievement_date' => Carbon::now()->subDays(rand(1, 730)),
                'level' => rand(0, 1) ? 'school' : (rand(0, 1) ? 'district' : 'national'),
                'awarder' => rand(0, 1) ? 'University' : null,
            ]);
        }
    }

    private function createAcademicHistory($student)
    {
        // Generate realistic academic history based on student's year and program
        $yearToSemesters = [
            '1st Year' => 2,
            '2nd Year' => 4,
            '3rd Year' => 6,
            '4th Year' => 8,
        ];

        $totalSemesters = $yearToSemesters[$student->standing] ?? 2;
        
        // Course options based on program
        $courseOptions = $student->program === 'BSIT' ? [
            ['IT101', 'Introduction to Information Technology', 3.0],
            ['IT102', 'Computer Programming 1', 3.0],
            ['IT103', 'Web Development', 3.0],
            ['IT104', 'Database Management', 3.0],
            ['IT105', 'Network Fundamentals', 3.0],
            ['IT106', 'Systems Analysis', 3.0],
            ['IT107', 'IT Project Management', 3.0],
            ['IT108', 'Information Security', 3.0],
        ] : [
            ['CS101', 'Introduction to Computer Science', 4.0],
            ['CS102', 'Programming Fundamentals', 4.0],
            ['CS103', 'Data Structures and Algorithms', 4.0],
            ['CS104', 'Computer Architecture', 3.0],
            ['CS105', 'Operating Systems', 3.0],
            ['CS106', 'Software Engineering', 3.0],
            ['CS107', 'Artificial Intelligence', 3.0],
            ['CS108', 'Machine Learning', 3.0],
        ];

        $semesters = ['First Semester', 'Second Semester'];
        $statuses = ['completed', 'completed', 'completed', 'in_progress'];

        for ($i = 0; $i < $totalSemesters; $i++) {
            $semesterIndex = $i % 2;
            $year = 2020 + floor($i / 2) + 1;
            
            // Select random courses for this semester
            $numCourses = rand(4, 6);
            $courseKeys = array_rand($courseOptions, $numCourses);
            
            if (!is_array($courseKeys)) {
                $courseKeys = [$courseKeys];
            }

            foreach ($courseKeys as $key) {
                $course = $courseOptions[$key];
                
                // Generate realistic grades (higher for advanced students)
                $baseGrade = $student->standing === '4th Year' ? 3.2 : 
                             ($student->standing === '3rd Year' ? 2.8 : 2.5);
                $grade = $baseGrade + (rand(-5, 10) / 10);
                $grade = max(1.0, min(4.0, $grade));

                AcademicHistory::create([
                    'stud_id' => $student->stud_id,
                    'course_id' => null,
                    'course_code' => $course[0],
                    'course_name' => $course[1],
                    'semester' => $semesters[$semesterIndex],
                    'year' => $year,
                    'grade' => $i < $totalSemesters - 1 ? $grade : null,
                    'credits' => $course[2],
                    'grade_letter' => $this->getGradeLetter($grade),
                    'status' => $i < $totalSemesters - 1 ? 'completed' : 'in_progress',
                ]);
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
