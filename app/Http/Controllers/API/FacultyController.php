<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Faculty;
use App\Models\Schedule;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\Rule;

class FacultyController extends Controller
{
    public function index(Request $request): JsonResponse
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
            $query->byDepartment($request->get('department'));
        }

        if ($request->has('employment_type')) {
            $query->byEmploymentType($request->get('employment_type'));
        }

        if ($request->has('status')) {
            $query->where('status', $request->get('status'));
        }

        $faculty = $query->paginate($request->get('per_page', 15));

        return response()->json($faculty);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'faculty_id' => 'required|string|max:20|unique:faculty',
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'email' => 'required|email|unique:faculty',
            'phone' => 'nullable|string|max:20',
            'birth_date' => 'required|date',
            'gender' => 'required|in:Male,Female,Other',
            'address' => 'required|string',
            'city' => 'required|string|max:255',
            'province' => 'required|string|max:255',
            'postal_code' => 'required|string|max:20',
            'employment_type' => 'required|in:Full-time,Part-time,Contract,Adjunct',
            'department' => 'required|string|max:255',
            'position' => 'required|string|max:255',
            'specialization' => 'nullable|string|max:255',
            'hire_date' => 'required|date',
            'status' => 'required|in:Active,Inactive,On Leave',
            'salary' => 'nullable|numeric|min:0',
            'education_background' => 'nullable|string',
            'certifications' => 'nullable|array',
            'bio' => 'nullable|string',
        ]);

        $faculty = Faculty::create($validated);

        return response()->json($faculty, 201);
    }

    public function show(Faculty $faculty): JsonResponse
    {
        $faculty->load(['schedules.curriculum', 'syllabus.curriculum', 'lessons.curriculum']);
        return response()->json($faculty);
    }

    public function update(Request $request, Faculty $faculty): JsonResponse
    {
        $validated = $request->validate([
            'faculty_id' => 'required|string|max:20|' . Rule::unique('faculty')->ignore($faculty->id),
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'email' => 'required|email|' . Rule::unique('faculty')->ignore($faculty->id),
            'phone' => 'nullable|string|max:20',
            'birth_date' => 'required|date',
            'gender' => 'required|in:Male,Female,Other',
            'address' => 'required|string',
            'city' => 'required|string|max:255',
            'province' => 'required|string|max:255',
            'postal_code' => 'required|string|max:20',
            'employment_type' => 'required|in:Full-time,Part-time,Contract,Adjunct',
            'department' => 'required|string|max:255',
            'position' => 'required|string|max:255',
            'specialization' => 'nullable|string|max:255',
            'hire_date' => 'required|date',
            'status' => 'required|in:Active,Inactive,On Leave',
            'salary' => 'nullable|numeric|min:0',
            'education_background' => 'nullable|string',
            'certifications' => 'nullable|array',
            'bio' => 'nullable|string',
        ]);

        $faculty->update($validated);

        return response()->json($faculty);
    }

    public function destroy(Faculty $faculty): JsonResponse
    {
        $faculty->delete();
        return response()->json(null, 204);
    }

    public function courses(Faculty $faculty): JsonResponse
    {
        $courses = $faculty->schedules()
            ->with('curriculum')
            ->where('status', 'Active')
            ->get()
            ->pluck('curriculum')
            ->unique('id');

        return response()->json($courses);
    }

    public function schedule(Faculty $faculty, Request $request): JsonResponse
    {
        $query = $faculty->schedules()->with('curriculum');

        if ($request->has('semester')) {
            $query->where('semester', $request->get('semester'));
        }

        if ($request->has('academic_year')) {
            $query->where('academic_year', $request->get('academic_year'));
        }

        $schedules = $query->orderBy('day_of_week')
                           ->orderBy('start_time')
                           ->get();

        return response()->json($schedules);
    }
}
