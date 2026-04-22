<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\Faculty;
use App\Models\CoursesCS;
use App\Models\CoursesIT;
use App\Models\Event;
use App\Models\AcademicHistory;
use App\Models\Enrollment;
use App\Models\Admin;
use App\Models\AdminAccount;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class DashboardController extends Controller
{
    public function adminStudents(): JsonResponse
    {
        $students = Student::select([
            'stud_id',
            'stud_num',
            'fname',
            'mname', 
            'lname',
            'ext',
            'email',
            'contact_num',
            'address',
            'program',
            'program_code',
            'section_id',
            'standing',
            'academic_status',
            'created_at'
        ])->get();

        // Transform data to match frontend format
        $transformedStudents = $students->map(function ($student) {
            $fullName = $student->fname . ' ' . $student->lname;
            if ($student->mname) {
                $fullName = $student->fname . ' ' . $student->mname . ' ' . $student->lname;
            }
            if ($student->ext) {
                $fullName .= ' ' . $student->ext;
            }

            return [
                'id' => $student->stud_id,
                'name' => $fullName,
                'email' => $student->email,
                'studentId' => $student->stud_num ?? $student->stud_id,
                'department' => $student->program ?? 'Unknown',
                'year' => $student->standing ?? '1st',
                'gpa' => '0.0', // This would need to be calculated from academic_history
                'status' => $student->academic_status === 'Enrolled' ? 'active' : 'inactive',
                'enrollmentDate' => $student->created_at ? $student->created_at->format('Y-m-d') : 'Unknown',
                'courses' => 0, // This would need to be calculated from academic_history
                'achievements' => 0, // This would need to be calculated from achievements table
                'violations' => 0, // This would need to be calculated from violations table
                'phone' => $student->contact_num ?? 'N/A',
                'address' => $student->address ?? 'N/A'
            ];
        });

        return response()->json($transformedStudents);
    }

    public function createStudent(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'fname' => 'required|string|max:255',
            'mname' => 'nullable|string|max:255',
            'lname' => 'required|string|max:255',
            'ext' => 'nullable|string|max:50',
            'gender' => 'required|string|in:Male,Female,Other',
            'bday' => 'required|date',
            'email' => 'required|email|unique:students,email',
            'contact_num' => 'nullable|string|max:50',
            'address' => 'required|string',
            'stud_num' => 'required|string|unique:students,stud_num',
            'guardian' => 'required|string|max:255',
            'program' => 'required|string|in:BSIT,BSCS',
            'program_code' => 'required|string|max:50',
            'section_id' => 'required|string|max:50',
            'course' => 'required|string|max:255',
            'achievements' => 'nullable|string',
            'skills' => 'nullable|string',
            'affiliations' => 'nullable|string',
            'violations' => 'nullable|string',
            'medicalRecord' => 'nullable|string',
            'standing' => 'required|string|in:1st,2nd,3rd,4th',
            'academic_status' => 'required|string|in:Enrolled,Not Enrolled,On Leave,Graduated,Dropped',
        ]);

        $student = Student::create($validated);

        return response()->json($student, 201);
    }

    public function studentStats(): JsonResponse
    {
        $bsitStudents = Student::where('program', 'BSIT')->count();
        $bscsStudents = Student::where('program', 'BSCS')->count();
        $totalStudents = Student::count();
        
        $stats = [
            'total' => $totalStudents,
            'bsitStudents' => $bsitStudents,
            'bscsStudents' => $bscsStudents,
            'totalCourses' => CoursesCS::count() + CoursesIT::count(),
            'totalAchievements' => 0 // This would need to be calculated from achievements table
        ];

        return response()->json($stats);
    }

    public function stats(): JsonResponse
    {
        $totalCourses = CoursesCS::count() + CoursesIT::count();
        
        $stats = [
            'totalStudents' => Student::count(),
            'totalFaculty' => Faculty::count(),
            'totalCourses' => $totalCourses,
            'totalEvents' => 0, // Events table doesn't exist yet
        ];

        return response()->json($stats);
    }

    public function adminStats(): JsonResponse
    {
        $now = Carbon::now();
        $lastMonth = $now->copy()->subMonth();
        
        // Calculate growth percentages
        $studentsCurrent = Student::count();
        $studentsLastMonth = Student::where('created_at', '<', $lastMonth)->count();
        $studentGrowth = $studentsLastMonth > 0 ? 
            (($studentsCurrent - $studentsLastMonth) / $studentsLastMonth * 100) : 0;

        $facultyCurrent = Faculty::count();
        $facultyLastMonth = Faculty::where('created_at', '<', $lastMonth)->count();
        $facultyGrowth = $facultyLastMonth > 0 ? 
            (($facultyCurrent - $facultyLastMonth) / $facultyLastMonth * 100) : 0;

        $coursesCurrent = CoursesCS::count() + CoursesIT::count();
        $coursesLastMonth = CoursesCS::where('created_at', '<', $lastMonth)->count() + CoursesIT::where('created_at', '<', $lastMonth)->count();
        $courseGrowth = $coursesLastMonth > 0 ? 
            (($coursesCurrent - $coursesLastMonth) / $coursesLastMonth * 100) : 0;

        // Total users (students + faculty + admins)
        $totalUsers = $studentsCurrent + $facultyCurrent + Admin::count();
        $usersLastMonth = Student::where('created_at', '<', $lastMonth)->count() + 
                          Faculty::where('created_at', '<', $lastMonth)->count() + 
                          Admin::where('created_at', '<', $lastMonth)->count();
        $userGrowth = $usersLastMonth > 0 ? 
            (($totalUsers - $usersLastMonth) / $usersLastMonth * 100) : 0;

        $stats = [
            'totalStudents' => number_format($studentsCurrent),
            'studentGrowth' => round($studentGrowth, 1),
            'totalFaculty' => number_format($facultyCurrent),
            'facultyGrowth' => round($facultyGrowth, 1),
            'totalCourses' => number_format($coursesCurrent),
            'courseGrowth' => round($courseGrowth, 1),
            'totalUsers' => number_format($totalUsers),
            'userGrowth' => round($userGrowth, 1),
        ];

        return response()->json($stats);
    }

    public function recentActivities(): JsonResponse
    {
        $activities = [];

        // Recent student enrollments
        $recentStudents = Student::orderBy('created_at', 'desc')
            ->take(3)
            ->get(['stud_id', 'fname', 'lname', 'program', 'created_at']);

        foreach ($recentStudents as $student) {
            $activities[] = [
                'id' => 'student_' . $student->stud_id,
                'type' => 'student_registration',
                'message' => "{$student->fname} {$student->lname} (ID: {$student->stud_id}) registered for {$student->program} program",
                'time' => $student->created_at->diffForHumans(),
                'icon' => 'Users',
                'color' => 'text-blue-500'
            ];
        }

        // Recent course updates from CS courses
        $recentCSCourses = CoursesCS::orderBy('updated_at', 'desc')
            ->take(1)
            ->get(['course_id', 'course', 'course_code', 'updated_at']);

        foreach ($recentCSCourses as $course) {
            $activities[] = [
                'id' => 'course_' . $course->course_id,
                'type' => 'course_update',
                'message' => "CS Course \"{$course->course} ({$course->course_code})\" updated",
                'time' => $course->updated_at->diffForHumans(),
                'icon' => 'BookOpen',
                'color' => 'text-green-500'
            ];
        }

        // Recent course updates from IT courses
        $recentITCourses = CoursesIT::orderBy('updated_at', 'desc')
            ->take(1)
            ->get(['course_id', 'course', 'course_code', 'updated_at']);

        foreach ($recentITCourses as $course) {
            $activities[] = [
                'id' => 'course_' . $course->course_id,
                'type' => 'course_update',
                'message' => "IT Course \"{$course->course} ({$course->course_code})\" updated",
                'time' => $course->updated_at->diffForHumans(),
                'icon' => 'BookOpen',
                'color' => 'text-green-500'
            ];
        }

        // Recent academic activities (grades submitted)
        $recentGrades = AcademicHistory::orderBy('updated_at', 'desc')
            ->take(2)
            ->get(['stud_id', 'course_code', 'grade', 'updated_at']);

        foreach ($recentGrades as $grade) {
            $student = Student::find($grade->stud_id);
            $activities[] = [
                'id' => 'grade_' . $grade->id,
                'type' => 'grade_submission',
                'message' => "Grade submitted for {$student->fname} {$student->lname} in {$grade->course_code}",
                'time' => $grade->updated_at->diffForHumans(),
                'icon' => 'FileText',
                'color' => 'text-purple-500'
            ];
        }

        // Events table doesn't exist, so we'll skip events for now
        // This can be added later when the events table is created

        // Sort by time and limit to 10 most recent
        usort($activities, function($a, $b) {
            return strtotime($b['time']) - strtotime($a['time']);
        });

        return response()->json(array_slice($activities, 0, 10));
    }
}
