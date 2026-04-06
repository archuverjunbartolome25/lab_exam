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

class StudentProfilingSeeder extends Seeder
{
    public function run(): void
    {
        // Get existing students
        $students = Student::all();

        foreach ($students as $student) {
            // Create affiliations
            $this->createAffiliations($student);
            
            // Create skills
            $this->createSkills($student);
            
            // Create violations (some students)
            if (rand(1, 10) <= 3) { // 30% chance of violations
                $this->createViolations($student);
            }
            
            // Create achievements
            $this->createAchievements($student);
            
            // Create academic history
            $this->createAcademicHistory($student);
        }
    }

    private function createAffiliations($student)
    {
        $affiliations = [
            ['Programming Club', 'club', 'Member'],
            ['Basketball Team', 'team', 'Player'],
            ['Student Council', 'organization', 'Representative'],
            ['Debate Society', 'club', 'Member'],
            ['Computer Science Society', 'organization', 'Member'],
            ['Drama Club', 'club', 'Actor'],
            ['Volunteer Corps', 'organization', 'Volunteer'],
        ];

        $numAffiliations = rand(1, 3);
        $affiliationKeys = array_rand($affiliations, $numAffiliations);
        if (!is_array($affiliationKeys)) {
            $affiliationKeys = [$affiliationKeys];
        }

        foreach ($affiliationKeys as $key) {
            $affiliation = $affiliations[$key];
            StudentAffiliation::create([
                'stud_id' => $student->stud_id,
                'type' => $affiliation[1],
                'name' => $affiliation[0],
                'role' => $affiliation[2],
                'start_date' => Carbon::now()->subMonths(rand(6, 24)),
                'end_date' => rand(0, 1) ? null : Carbon::now()->addMonths(rand(1, 12)),
                'description' => "Active participation in {$affiliation[0]}",
            ]);
        }
    }

    private function createSkills($student)
    {
        $skills = [
            ['technical', 'JavaScript', 'intermediate'],
            ['technical', 'Python', 'advanced'],
            ['technical', 'Java', 'intermediate'],
            ['technical', 'Web Development', 'advanced'],
            ['technical', 'Database Management', 'intermediate'],
            ['soft', 'Leadership', 'advanced'],
            ['soft', 'Communication', 'intermediate'],
            ['soft', 'Teamwork', 'advanced'],
            ['soft', 'Problem Solving', 'advanced'],
            ['language', 'English', 'advanced'],
            ['language', 'Filipino', 'intermediate'],
            ['language', 'Spanish', 'beginner'],
        ];

        $numSkills = rand(3, 8);
        $skillKeys = array_rand($skills, $numSkills);
        if (!is_array($skillKeys)) {
            $skillKeys = [$skillKeys];
        }

        foreach ($skillKeys as $key) {
            $skill = $skills[$key];
            StudentSkill::create([
                'stud_id' => $student->stud_id,
                'category' => $skill[0],
                'name' => $skill[1],
                'level' => $skill[2],
                'description' => "Proficient in {$skill[1]} at {$skill[2]} level",
                'acquired_date' => Carbon::now()->subMonths(rand(1, 36)),
                'certificate' => rand(0, 1) ? "{$skill[1]} Certification" : null,
            ]);
        }
    }

    private function createViolations($student)
    {
        $violations = [
            ['academic', 'minor', 'Late submission of assignment'],
            ['disciplinary', 'minor', 'Unauthorized absence'],
            ['attendance', 'major', 'Excessive tardiness'],
            ['academic', 'major', 'Plagiarism detected'],
            ['disciplinary', 'critical', 'Fighting on campus'],
            ['attendance', 'minor', 'Skipping class'],
        ];

        $numViolations = rand(1, 2);
        $violationKeys = array_rand($violations, $numViolations);
        if (!is_array($violationKeys)) {
            $violationKeys = [$violationKeys];
        }

        foreach ($violationKeys as $key) {
            $violation = $violations[$key];
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
        $achievements = [
            ['academic', 'Dean\'s List', 'Outstanding academic performance for the semester'],
            ['athletic', 'Basketball MVP', 'Most valuable player in inter-college tournament'],
            ['extracurricular', 'Best Speaker', 'Won debate competition'],
            ['academic', 'Honor Roll', 'Consistent high grades throughout the year'],
            ['athletic', 'Championship Winner', 'Part of championship basketball team'],
            ['extracurricular', 'Leadership Award', 'Outstanding leadership in student council'],
            ['academic', 'Research Excellence', 'Published research paper in student journal'],
            ['extracurricular', 'Community Service', '100+ hours of community service'],
        ];

        $numAchievements = rand(1, 4);
        $achievementKeys = array_rand($achievements, $numAchievements);
        if (!is_array($achievementKeys)) {
            $achievementKeys = [$achievementKeys];
        }

        foreach ($achievementKeys as $key) {
            $achievement = $achievements[$key];
            StudentAchievement::create([
                'stud_id' => $student->stud_id,
                'type' => $achievement[0],
                'title' => $achievement[1],
                'description' => $achievement[2],
                'achievement_date' => Carbon::now()->subDays(rand(1, 365)),
                'level' => rand(0, 1) ? 'school' : (rand(0, 1) ? 'district' : 'national'),
                'awarder' => rand(0, 1) ? 'University' : null,
            ]);
        }
    }

    private function createAcademicHistory($student)
    {
        $courses = [
            ['CCS101', 'Introduction to Computing', 3.0, 3.0],
            ['CCS102', 'Computer Programming 1', 3.5, 3.0],
            ['CCS103', 'Data Structures', 2.8, 4.0],
            ['CCS104', 'Web Development', 3.2, 3.0],
            ['CCS105', 'Database Systems', 3.7, 3.0],
            ['MATH101', 'College Algebra', 2.5, 3.0],
            ['ENG101', 'English Communication', 3.8, 3.0],
            ['PHYS101', 'Physics 1', 2.9, 4.0],
        ];

        $semesters = ['First Semester', 'Second Semester'];
        $statuses = ['completed', 'completed', 'completed', 'completed', 'in_progress'];

        $numCourses = rand(4, 8);
        $courseKeys = array_rand($courses, $numCourses);
        if (!is_array($courseKeys)) {
            $courseKeys = [$courseKeys];
        }

        foreach ($courseKeys as $index => $key) {
            $course = $courses[$key];
            AcademicHistory::create([
                'stud_id' => $student->stud_id,
                'course_id' => null, // Will be linked when courses table is ready
                'course_code' => $course[0],
                'course_name' => $course[1],
                'semester' => $semesters[array_rand($semesters)],
                'year' => 2023 + rand(0, 2),
                'grade' => $course[2],
                'credits' => $course[3],
                'grade_letter' => $this->getGradeLetter($course[2]),
                'status' => $statuses[$index] ?? 'completed',
            ]);
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
