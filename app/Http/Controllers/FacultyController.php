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
}
