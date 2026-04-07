<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Student;
use App\Models\Faculty;
use App\Models\CoursesIT;
use App\Models\CoursesCS;
use App\Models\SectionsIT;
use App\Models\SectionsCS;
use Inertia\Inertia;

class FacultyController extends Controller
{
    public function dashboard()
    {
        $stats = [
            'total_students' => Student::count(),
            'total_courses' => CoursesIT::count() + CoursesCS::count(),
            'total_sections' => SectionsIT::count() + SectionsCS::count(),
        ];

        return Inertia::render('Faculty/Dashboard', [
            'stats' => $stats
        ]);
    }

    public function students()
    {
        // Get sections with enrolled students
        $sectionsIT = SectionsIT::all();
        $sectionsCS = SectionsCS::all();
        
        // Get enrolled students for each section using section_id relationship
        $sectionsIT->each(function ($section) {
            $section->enrolled_students = Student::with([
                'affiliations',
                'skills', 
                'violations',
                'achievements',
                'academicHistory',
                'medicalRecord'
            ])
            ->where('section_id', $section->section_id)
            ->where('program', 'BSIT')
            ->get()
            ->each(function ($student) {
                // Add computed properties
                $student->current_gpa = $student->current_gpa;
                $student->total_credits = $student->total_credits;
                $student->active_violations = $student->active_violations;
            });
        });
        
        $sectionsCS->each(function ($section) {
            $section->enrolled_students = Student::with([
                'affiliations',
                'skills', 
                'violations',
                'achievements',
                'academicHistory',
                'medicalRecord'
            ])
            ->where('section_id', $section->section_id)
            ->where('program', 'BSCS')
            ->get()
            ->each(function ($student) {
                // Add computed properties
                $student->current_gpa = $student->current_gpa;
                $student->total_credits = $student->total_credits;
                $student->active_violations = $student->active_violations;
            });
        });
        
        $allSections = $sectionsIT->concat($sectionsCS);
        
        // Also get all students for backward compatibility
        $students = Student::with([
            'affiliations',
            'skills', 
            'violations',
            'achievements',
            'academicHistory'
        ])->get();
        
        // Load actual course information for each student
        $students->each(function ($student) {
            if ($student->courses && is_array($student->courses)) {
                $courseIds = $student->courses;
                $courses = [];
                
                foreach ($courseIds as $courseId) {
                    if ($student->program === 'BSIT') {
                        $course = CoursesIT::find($courseId);
                        if ($course) {
                            $courses[] = [
                                'id' => $course->course_id,
                                'code' => $course->course_code,
                                'name' => $course->course,
                                'credits' => $course->credits
                            ];
                        }
                    } elseif ($student->program === 'BSCS') {
                        $course = CoursesCS::find($courseId);
                        if ($course) {
                            $courses[] = [
                                'id' => $course->course_id,
                                'code' => $course->course_code,
                                'name' => $course->course,
                                'credits' => $course->credits
                            ];
                        }
                    }
                }
                
                $student->courses_info = $courses;
            }
            
            // Add computed properties
            $student->current_gpa = $student->current_gpa;
            $student->total_credits = $student->total_credits;
            $student->active_violations = $student->active_violations;
        });
        
        return Inertia::render('Faculty/Students', [
            'students' => $students,
            'sections' => $allSections
        ]);
    }

    public function createStudent()
    {
        // Get data needed for student creation form
        $sectionsIT = SectionsIT::all();
        $sectionsCS = SectionsCS::all();
        $coursesIT = CoursesIT::all();
        $coursesCS = CoursesCS::all();
        
        return Inertia::render('Faculty/CreateStudent', [
            'sectionsIT' => $sectionsIT,
            'sectionsCS' => $sectionsCS,
            'coursesIT' => $coursesIT,
            'coursesCS' => $coursesCS
        ]);
    }

    public function storeStudent(Request $request)
    {
        $validated = $request->validate([
            'student_id' => 'required|string|max:20|unique:students,stud_num',
            'first_name' => 'required|string|max:100',
            'last_name' => 'required|string|max:100',
            'middle_name' => 'nullable|string|max:100',
            'ext' => 'nullable|string|max:20',
            'email' => 'required|email|max:150|unique:students',
            'phone' => 'required|string|max:20',
            'birth_date' => 'required|date',
            'gender' => 'required|in:Male,Female,Other',
            'address' => 'required|string',
            'program' => 'required|in:BSIT,BSCS',
            'program_code' => 'required|string|max:10',
            'section_id' => 'required|integer',
            'courses' => 'nullable|array',
            'standing' => 'required|string|max:20', // 1st, 2nd, 3rd, 4th year
            'academic_status' => 'required|in:Regular,Irregular',
        ]);

        // Validate section_id exists in the correct table based on program
        $sectionTable = $validated['program'] === 'BSIT' ? 'sections_it' : 'sections_cs';
        if (!\DB::table($sectionTable)->where('section_id', $validated['section_id'])->exists()) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['section_id' => 'The selected section is invalid.']);
        }

        // Map form fields to database columns
        $studentData = [
            'stud_num' => $validated['student_id'],
            'fname' => $validated['first_name'],
            'lname' => $validated['last_name'],
            'mname' => $validated['middle_name'] ?? null,
            'ext' => $validated['ext'] ?? null,
            'email' => $validated['email'],
            'contact_num' => $validated['phone'],
            'bday' => $validated['birth_date'],
            'gender' => $validated['gender'],
            'address' => $validated['address'],
            'program' => $validated['program'],
            'program_code' => $validated['program_code'],
            'section_id' => $validated['section_id'],
            'courses' => $validated['courses'] ?? null,
            'standing' => $validated['standing'],
            'academic_status' => $validated['academic_status'],
        ];

        $student = Student::create($studentData);

        return redirect()->route('faculty.students')
            ->with('success', 'Student created successfully!');
    }

    public function showStudent(Student $student)
    {
        // Load student with achievements relationship
        $student->load(['achievements']);

        // Get section information
        $section = null;
        if ($student->program === 'BSIT') {
            $section = SectionsIT::find($student->section_id);
        } elseif ($student->program === 'BSCS') {
            $section = SectionsCS::find($student->section_id);
        }

        // Get course information
        $courses = [];
        if ($student->courses && is_array($student->courses)) {
            foreach ($student->courses as $courseId) {
                if ($student->program === 'BSIT') {
                    $course = CoursesIT::find($courseId);
                    if ($course) {
                        $courses[] = [
                            'id' => $course->course_id,
                            'code' => $course->course_code,
                            'name' => $course->course,
                            'credits' => $course->credits
                        ];
                    }
                } elseif ($student->program === 'BSCS') {
                    $course = CoursesCS::find($courseId);
                    if ($course) {
                        $courses[] = [
                            'id' => $course->course_id,
                            'code' => $course->course_code,
                            'name' => $course->course,
                            'credits' => $course->credits
                        ];
                    }
                }
            }
        }

        // Get available courses for adding
        $availableCourses = [];
        if ($student->program === 'BSIT') {
            $availableCourses = CoursesIT::all();
        } elseif ($student->program === 'BSCS') {
            $availableCourses = CoursesCS::all();
        }

        return Inertia::render('Faculty/StudentDetail', [
            'student' => $student,
            'section' => $section,
            'courses' => $courses,
            'availableCourses' => $availableCourses
        ]);
    }

    public function sections()
    {
        $sectionsIT = SectionsIT::all();
        $sectionsCS = SectionsCS::all();
        
        // Get enrolled students for each section with comprehensive data
        $sectionsIT->each(function ($section) {
            $sectionCourses = json_decode($section->courses, true);
            $section->enrolled_students = Student::with([
                'affiliations',
                'skills', 
                'violations',
                'achievements',
                'academicHistory',
                'medicalRecord'
            ])
            ->where('program', 'BSIT')
            ->where(function($query) use ($sectionCourses) {
                foreach ($sectionCourses as $course) {
                    $query->orWhereJsonContains('courses', $course);
                }
            })
            ->get()
            ->each(function ($student) {
                // Add computed properties
                $student->current_gpa = $student->current_gpa;
                $student->total_credits = $student->total_credits;
                $student->active_violations = $student->active_violations;
            });
        });
        
        $sectionsCS->each(function ($section) {
            $sectionCourses = json_decode($section->courses, true);
            $section->enrolled_students = Student::with([
                'affiliations',
                'skills', 
                'violations',
                'achievements',
                'academicHistory',
                'medicalRecord'
            ])
            ->where('program', 'BSCS')
            ->where(function($query) use ($sectionCourses) {
                foreach ($sectionCourses as $course) {
                    $query->orWhereJsonContains('courses', $course);
                }
            })
            ->get()
            ->each(function ($student) {
                // Add computed properties
                $student->current_gpa = $student->current_gpa;
                $student->total_credits = $student->total_credits;
                $student->active_violations = $student->active_violations;
            });
        });
        
        $allSections = $sectionsIT->concat($sectionsCS);
        
        return Inertia::render('Faculty/Sections', [
            'sections' => $allSections
        ]);
    }

    public function courses()
    {
        // Get sections with enrolled students and course info
        $sectionsIT = SectionsIT::all();
        $sectionsCS = SectionsCS::all();
        
        // Get enrolled students for each section
        $sectionsIT->each(function ($section) {
            $section->enrolled_students = Student::where('section_id', $section->section_id)
                ->where('program', 'BSIT')
                ->get();
        });
        
        $sectionsCS->each(function ($section) {
            $section->enrolled_students = Student::where('section_id', $section->section_id)
                ->where('program', 'BSCS')
                ->get();
        });
        
        $allSections = $sectionsIT->concat($sectionsCS);
        
        return Inertia::render('Faculty/Courses', [
            'sections' => $allSections
        ]);
    }

    public function grades()
    {
        // For now, return students data for grades view
        $students = Student::all();
        
        return Inertia::render('Faculty/Grades', [
            'students' => $students
        ]);
    }

    public function reports()
    {
        // Sample reports data
        $reports = [
            [
                'id' => 1,
                'title' => 'Student Enrollment Report',
                'type' => 'Enrollment',
                'date' => '2026-03-10',
                'status' => 'Completed'
            ],
            [
                'id' => 2,
                'title' => 'Academic Performance Report',
                'type' => 'Academic',
                'date' => '2026-03-09',
                'status' => 'Completed'
            ]
        ];
        
        return Inertia::render('Faculty/Reports', [
            'reports' => $reports
        ]);
    }

    public function settings()
    {
        // Get current faculty user (for now, get first faculty)
        $faculty = Faculty::first();
        
        return Inertia::render('Faculty/Settings', [
            'faculty' => $faculty
        ]);
    }

    // Course Management
    public function addCourse(Student $student, Request $request)
    {
        $validated = $request->validate([
            'course_id' => 'required|integer',
        ]);

        // Get current courses and add new one
        $currentCourses = $student->courses ? $student->courses : [];
        if (!is_array($currentCourses)) {
            $currentCourses = [];
        }
        $currentCourses[] = $validated['course_id'];
        
        $student->update(['courses' => $currentCourses]);

        return redirect()->back()->with('success', 'Course added successfully!');
    }

    public function removeCourse(Student $student, $course)
    {
        $currentCourses = $student->courses ? $student->courses : [];
        if (!is_array($currentCourses)) {
            $currentCourses = [];
        }
        $currentCourses = array_filter($currentCourses, function($c) use ($course) {
            return $c != $course;
        });
        
        $student->update(['courses' => array_values($currentCourses)]);

        return redirect()->back()->with('success', 'Course removed successfully!');
    }

    // Achievement Management
    public function addAchievement(Student $student, Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'date' => 'required|date',
            'type' => 'required|in:Academic,Sports,Cultural,Leadership',
            'level' => 'nullable|string|max:100',
        ]);

        // Create achievement in student_achievements table
        \App\Models\StudentAchievement::create([
            'stud_id' => $student->stud_id,
            'title' => $validated['title'],
            'description' => $validated['description'],
            'achievement_date' => $validated['date'],
            'type' => $validated['type'],
            'level' => $validated['level'] ?? 'School',
            'awarder' => 'Faculty',
        ]);

        return redirect()->back()->with('success', 'Achievement added successfully!');
    }

    public function removeAchievement(Student $student, $achievement)
    {
        \App\Models\StudentAchievement::where('id', $achievement)
            ->where('stud_id', $student->stud_id)
            ->delete();

        return redirect()->back()->with('success', 'Achievement removed successfully!');
    }

    // Skills Management
    public function addSkill(Student $student, Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'level' => 'required|in:Beginner,Intermediate,Advanced,Expert',
            'category' => 'nullable|string|max:100',
        ]);

        $skills = $student->skills ? $student->skills : [];
        if (!is_array($skills)) {
            $skills = [];
        }
        $skills[] = array_merge($validated, ['id' => time()]);

        $student->update(['skills' => $skills]);

        return redirect()->back()->with('success', 'Skill added successfully!');
    }

    public function removeSkill(Student $student, $skill)
    {
        $skills = $student->skills ? $student->skills : [];
        if (!is_array($skills)) {
            $skills = [];
        }
        $skills = array_filter($skills, function($s) use ($skill) {
            return ($s['id'] ?? null) != $skill;
        });
        
        $student->update(['skills' => array_values($skills)]);

        return redirect()->back()->with('success', 'Skill removed successfully!');
    }

    // Affiliation Management
    public function addAffiliation(Student $student, Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:Organization,Club,Society,Team',
            'role' => 'required|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date',
        ]);

        $affiliations = $student->affiliations ? $student->affiliations : [];
        if (!is_array($affiliations)) {
            $affiliations = [];
        }
        $affiliations[] = array_merge($validated, ['id' => time()]);

        $student->update(['affiliations' => $affiliations]);

        return redirect()->back()->with('success', 'Affiliation added successfully!');
    }

    public function removeAffiliation(Student $student, $affiliation)
    {
        $affiliations = $student->affiliations ? $student->affiliations : [];
        if (!is_array($affiliations)) {
            $affiliations = [];
        }
        $affiliations = array_filter($affiliations, function($a) use ($affiliation) {
            return ($a['id'] ?? null) != $affiliation;
        });
        
        $student->update(['affiliations' => array_values($affiliations)]);

        return redirect()->back()->with('success', 'Affiliation removed successfully!');
    }

    // Violation Management
    public function addViolation(Student $student, Request $request)
    {
        $validated = $request->validate([
            'type' => 'required|string|max:255',
            'description' => 'required|string',
            'severity' => 'required|in:Minor,Moderate,Major,Severe',
            'date' => 'required|date',
            'status' => 'required|in:Active,Resolved,Expired',
        ]);

        $violations = $student->violations ? $student->violations : [];
        if (!is_array($violations)) {
            $violations = [];
        }
        $violations[] = array_merge($validated, [
            'id' => time(),
            'violation_date' => $validated['date']
        ]);

        $student->update(['violations' => $violations]);

        return redirect()->back()->with('success', 'Violation record added successfully!');
    }

    public function removeViolation(Student $student, $violation)
    {
        $violations = $student->violations ? $student->violations : [];
        if (!is_array($violations)) {
            $violations = [];
        }
        $violations = array_filter($violations, function($v) use ($violation) {
            return ($v['id'] ?? null) != $violation;
        });
        
        $student->update(['violations' => array_values($violations)]);

        return redirect()->back()->with('success', 'Violation record removed successfully!');
    }

    // Medical Records Management
    public function addMedicalRecord(Student $student, Request $request)
    {
        $validated = $request->validate([
            'condition' => 'required|string|max:255',
            'description' => 'required|string',
            'diagnosis_date' => 'required|date',
            'treatment' => 'nullable|string',
            'medications' => 'nullable|string',
            'doctor' => 'nullable|string|max:255',
            'emergency_contact' => 'nullable|string|max:255',
        ]);

        $medicalRecord = $student->medicalRecord ? $student->medicalRecord : [];
        if (!is_array($medicalRecord)) {
            $medicalRecord = [];
        }
        $medicalRecord[] = array_merge($validated, ['id' => time()]);

        $student->update(['medicalRecord' => $medicalRecord]);

        return redirect()->back()->with('success', 'Medical record added successfully!');
    }

    public function removeMedicalRecord(Student $student, $medical)
    {
        $medicalRecord = $student->medicalRecord ? $student->medicalRecord : [];
        if (!is_array($medicalRecord)) {
            $medicalRecord = [];
        }
        $medicalRecord = array_filter($medicalRecord, function($m) use ($medical) {
            return ($m['id'] ?? null) != $medical;
        });
        
        $student->update(['medicalRecord' => array_values($medicalRecord)]);

        return redirect()->back()->with('success', 'Medical record removed successfully!');
    }

    public function editStudent(Student $student, Request $request)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:100',
            'last_name' => 'required|string|max:100',
            'middle_name' => 'nullable|string|max:100',
            'ext' => 'nullable|string|max:20',
            'email' => 'required|email|max:150|unique:students,email,'.$student->stud_id,
            'phone' => 'required|string|max:20',
            'birth_date' => 'required|date',
            'gender' => 'required|in:Male,Female,Other',
            'address' => 'required|string',
            'program' => 'required|in:BSIT,BSCS',
            'program_code' => 'required|string|max:10',
            'section_id' => 'required|integer',
            'courses' => 'nullable|array',
            'standing' => 'required|string|max:20',
            'academic_status' => 'required|in:Regular,Irregular',
        ]);

        // Validate section_id exists in the correct table based on program
        $sectionTable = $validated['program'] === 'BSIT' ? 'sections_it' : 'sections_cs';
        if (!\DB::table($sectionTable)->where('section_id', $validated['section_id'])->exists()) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['section_id' => 'The selected section is invalid.']);
        }

        // Map form fields to database columns
        $studentData = [
            'fname' => $validated['first_name'],
            'lname' => $validated['last_name'],
            'mname' => $validated['middle_name'] ?? null,
            'ext' => $validated['ext'] ?? null,
            'email' => $validated['email'],
            'contact_num' => $validated['phone'],
            'bday' => $validated['birth_date'],
            'gender' => $validated['gender'],
            'address' => $validated['address'],
            'program' => $validated['program'],
            'program_code' => $validated['program_code'],
            'section_id' => $validated['section_id'],
            'courses' => $validated['courses'] ?? null,
            'standing' => $validated['standing'],
            'academic_status' => $validated['academic_status'],
        ];

        $student->update($studentData);

        return redirect()->back()->with('success', 'Student information updated successfully!');
    }
}
