<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\Faculty;
use App\Models\Curriculum;
use App\Models\Event;
use App\Models\Schedule;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class SearchController extends Controller
{
    public function search(Request $request): JsonResponse
    {
        $query = $request->get('q', '');
        $type = $request->get('type', 'all');
        $limit = $request->get('limit', 20);

        $results = [];

        if ($type === 'all' || $type === 'students') {
            $results['students'] = Student::where(function ($q) use ($query) {
                $q->where('first_name', 'like', "%{$query}%")
                  ->orWhere('last_name', 'like', "%{$query}%")
                  ->orWhere('student_id', 'like', "%{$query}%")
                  ->orWhere('email', 'like', "%{$query}%")
                  ->orWhere('course', 'like', "%{$query}%");
            })
            ->limit($limit)
            ->get(['id', 'student_id', 'first_name', 'last_name', 'email', 'course', 'section', 'year_level']);
        }

        if ($type === 'all' || $type === 'faculty') {
            $results['faculty'] = Faculty::where(function ($q) use ($query) {
                $q->where('first_name', 'like', "%{$query}%")
                  ->orWhere('last_name', 'like', "%{$query}%")
                  ->orWhere('faculty_id', 'like', "%{$query}%")
                  ->orWhere('email', 'like', "%{$query}%")
                  ->orWhere('department', 'like', "%{$query}%");
            })
            ->limit($limit)
            ->get(['id', 'faculty_id', 'first_name', 'last_name', 'email', 'department', 'position']);
        }

        if ($type === 'all' || $type === 'courses') {
            $results['courses'] = Curriculum::where(function ($q) use ($query) {
                $q->where('course_name', 'like', "%{$query}%")
                  ->orWhere('course_code', 'like', "%{$query}%")
                  ->orWhere('description', 'like', "%{$query}%")
                  ->orWhere('department', 'like', "%{$query}%");
            })
            ->limit($limit)
            ->get(['id', 'course_code', 'course_name', 'department', 'credits', 'course_type']);
        }

        if ($type === 'all' || $type === 'events') {
            $results['events'] = Event::where(function ($q) use ($query) {
                $q->where('title', 'like', "%{$query}%")
                  ->orWhere('description', 'like', "%{$query}%")
                  ->orWhere('venue', 'like', "%{$query}%")
                  ->orWhere('organizer', 'like', "%{$query}%");
            })
            ->limit($limit)
            ->get(['id', 'title', 'type', 'category', 'start_datetime', 'venue', 'status']);
        }

        if ($type === 'all' || $type === 'schedules') {
            $results['schedules'] = Schedule::with(['curriculum', 'faculty'])
                ->where(function ($q) use ($query) {
                    $q->where('section', 'like', "%{$query}%")
                      ->orWhere('room', 'like', "%{$query}%")
                      ->orWhere('building', 'like', "%{$query}%")
                      ->orWhereHas('curriculum', function ($subQ) use ($query) {
                          $subQ->where('course_name', 'like', "%{$query}%")
                               ->orWhere('course_code', 'like', "%{$query}%");
                      });
                })
                ->limit($limit)
                ->get();
        }

        return response()->json($results);
    }

    public function searchStudents(Request $request): JsonResponse
    {
        $query = Student::query();

        if ($request->has('search')) {
            $search = $request->get('search');
            $query->where(function ($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%")
                  ->orWhere('student_id', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        if ($request->has('course')) {
            $query->where('course', $request->get('course'));
        }

        if ($request->has('year_level')) {
            $query->where('year_level', $request->get('year_level'));
        }

        if ($request->has('section')) {
            $query->where('section', $request->get('section'));
        }

        if ($request->has('status')) {
            $query->where('status', $request->get('status'));
        }

        if ($request->has('gpa_min')) {
            $query->where('gpa', '>=', $request->get('gpa_min'));
        }

        if ($request->has('gpa_max')) {
            $query->where('gpa', '<=', $request->get('gpa_max'));
        }

        $students = $query->paginate($request->get('per_page', 15));

        return response()->json($students);
    }

    public function searchFaculty(Request $request): JsonResponse
    {
        $query = Faculty::query();

        if ($request->has('search')) {
            $search = $request->get('search');
            $query->where(function ($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%")
                  ->orWhere('faculty_id', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        if ($request->has('department')) {
            $query->where('department', $request->get('department'));
        }

        if ($request->has('position')) {
            $query->where('position', $request->get('position'));
        }

        if ($request->has('employment_type')) {
            $query->where('employment_type', $request->get('employment_type'));
        }

        if ($request->has('specialization')) {
            $query->where('specialization', 'like', "%{$request->get('specialization')}%");
        }

        if ($request->has('status')) {
            $query->where('status', $request->get('status'));
        }

        $faculty = $query->paginate($request->get('per_page', 15));

        return response()->json($faculty);
    }

    public function searchCourses(Request $request): JsonResponse
    {
        $query = Curriculum::query();

        if ($request->has('search')) {
            $search = $request->get('search');
            $query->where(function ($q) use ($search) {
                $q->where('course_name', 'like', "%{$search}%")
                  ->orWhere('course_code', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        if ($request->has('department')) {
            $query->where('department', $request->get('department'));
        }

        if ($request->has('course_type')) {
            $query->where('course_type', $request->get('course_type'));
        }

        if ($request->has('year_level')) {
            $query->where('year_level', $request->get('year_level'));
        }

        if ($request->has('credits_min')) {
            $query->where('credits', '>=', $request->get('credits_min'));
        }

        if ($request->has('credits_max')) {
            $query->where('credits', '<=', $request->get('credits_max'));
        }

        $courses = $query->paginate($request->get('per_page', 15));

        return response()->json($courses);
    }

    public function searchEvents(Request $request): JsonResponse
    {
        $query = Event::query();

        if ($request->has('search')) {
            $search = $request->get('search');
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('venue', 'like', "%{$search}%")
                  ->orWhere('organizer', 'like', "%{$search}%");
            });
        }

        if ($request->has('type')) {
            $query->where('type', $request->get('type'));
        }

        if ($request->has('category')) {
            $query->where('category', $request->get('category'));
        }

        if ($request->has('status')) {
            $query->where('status', $request->get('status'));
        }

        if ($request->has('start_date')) {
            $query->where('start_datetime', '>=', $request->get('start_date'));
        }

        if ($request->has('end_date')) {
            $query->where('end_datetime', '<=', $request->get('end_date'));
        }

        if ($request->has('venue')) {
            $query->where('venue', 'like', "%{$request->get('venue')}%");
        }

        $events = $query->orderBy('start_datetime')
                       ->paginate($request->get('per_page', 15));

        return response()->json($events);
    }
}
