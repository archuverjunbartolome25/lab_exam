<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\Enrollment;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\Rule;

class StudentController extends Controller
{
    public function index(Request $request): JsonResponse
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
            $query->byCourse($request->get('course'));
        }

        if ($request->has('year_level')) {
            $query->byYearLevel($request->get('year_level'));
        }

        if ($request->has('status')) {
            $query->where('status', $request->get('status'));
        }

        $students = $query->paginate($request->get('per_page', 15));

        return response()->json($students);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'student_id' => 'required|string|max:20|unique:students',
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'email' => 'required|email|unique:students',
            'phone' => 'nullable|string|max:20',
            'birth_date' => 'required|date',
            'gender' => 'required|in:Male,Female,Other',
            'address' => 'required|string',
            'city' => 'required|string|max:255',
            'province' => 'required|string|max:255',
            'postal_code' => 'required|string|max:20',
            'course' => 'required|string|max:255',
            'section' => 'required|string|max:50',
            'year_level' => 'required|integer|min:1|max:5',
            'semester' => 'required|string|max:50',
            'status' => 'required|in:Active,Inactive,Graduated,Dropped',
            'enrollment_date' => 'required|date',
            'guardian_name' => 'nullable|string|max:255',
            'guardian_contact' => 'nullable|string|max:20',
            'gpa' => 'nullable|numeric|min:0|max:5',
            'awards' => 'nullable|array',
        ]);

        $student = Student::create($validated);

        return response()->json($student, 201);
    }

    public function show(Student $student): JsonResponse
    {
        $student->load(['enrollments.curriculum', 'enrollments.schedule']);
        return response()->json($student);
    }

    public function update(Request $request, Student $student): JsonResponse
    {
        $validated = $request->validate([
            'student_id' => 'required|string|max:20|' . Rule::unique('students')->ignore($student->id),
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'email' => 'required|email|' . Rule::unique('students')->ignore($student->id),
            'phone' => 'nullable|string|max:20',
            'birth_date' => 'required|date',
            'gender' => 'required|in:Male,Female,Other',
            'address' => 'required|string',
            'city' => 'required|string|max:255',
            'province' => 'required|string|max:255',
            'postal_code' => 'required|string|max:20',
            'course' => 'required|string|max:255',
            'section' => 'required|string|max:50',
            'year_level' => 'required|integer|min:1|max:5',
            'semester' => 'required|string|max:50',
            'status' => 'required|in:Active,Inactive,Graduated,Dropped',
            'enrollment_date' => 'required|date',
            'guardian_name' => 'nullable|string|max:255',
            'guardian_contact' => 'nullable|string|max:20',
            'gpa' => 'nullable|numeric|min:0|max:5',
            'awards' => 'nullable|array',
        ]);

        $student->update($validated);

        return response()->json($student);
    }

    public function destroy(Student $student): JsonResponse
    {
        $student->delete();
        return response()->json(null, 204);
    }

    public function courses(Student $student): JsonResponse
    {
        $courses = $student->courses()->withPivot(['academic_year', 'semester', 'grade', 'status'])->get();
        return response()->json($courses);
    }

    public function grades(Student $student): JsonResponse
    {
        $grades = $student->enrollments()
            ->with('curriculum')
            ->whereNotNull('grade')
            ->orderBy('academic_year', 'desc')
            ->orderBy('semester', 'desc')
            ->get();

        return response()->json($grades);
    }
}
