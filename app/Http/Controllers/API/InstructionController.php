<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Curriculum;
use App\Models\Syllabus;
use App\Models\Lesson;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\Rule;

class InstructionController extends Controller
{
    public function index(Request $request): JsonResponse
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
            $query->byDepartment($request->get('department'));
        }

        if ($request->has('course_type')) {
            $query->byCourseType($request->get('course_type'));
        }

        if ($request->has('year_level')) {
            $query->byYearLevel($request->get('year_level'));
        }

        $curriculum = $query->paginate($request->get('per_page', 15));

        return response()->json($curriculum);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'course_code' => 'required|string|max:20|unique:curriculum',
            'course_name' => 'required|string|max:255',
            'description' => 'required|string',
            'department' => 'required|string|max:255',
            'credits' => 'required|integer|min:1|max:10',
            'hours_per_week' => 'required|integer|min:1|max:40',
            'course_type' => 'required|in:Core,Elective,Major,Minor',
            'prerequisites' => 'nullable|string|max:255',
            'year_level' => 'required|integer|min:1|max:5',
            'semester' => 'required|string|max:50',
            'status' => 'required|in:Active,Inactive,Archived',
            'learning_outcomes' => 'nullable|array',
            'assessment_methods' => 'nullable|array',
        ]);

        $curriculum = Curriculum::create($validated);

        return response()->json($curriculum, 201);
    }

    public function show(Curriculum $curriculum): JsonResponse
    {
        $curriculum->load(['syllabus.faculty', 'lessons.faculty', 'schedules.faculty']);
        return response()->json($curriculum);
    }

    public function update(Request $request, Curriculum $curriculum): JsonResponse
    {
        $validated = $request->validate([
            'course_code' => 'required|string|max:20|' . Rule::unique('curriculum')->ignore($curriculum->id),
            'course_name' => 'required|string|max:255',
            'description' => 'required|string',
            'department' => 'required|string|max:255',
            'credits' => 'required|integer|min:1|max:10',
            'hours_per_week' => 'required|integer|min:1|max:40',
            'course_type' => 'required|in:Core,Elective,Major,Minor',
            'prerequisites' => 'nullable|string|max:255',
            'year_level' => 'required|integer|min:1|max:5',
            'semester' => 'required|string|max:50',
            'status' => 'required|in:Active,Inactive,Archived',
            'learning_outcomes' => 'nullable|array',
            'assessment_methods' => 'nullable|array',
        ]);

        $curriculum->update($validated);

        return response()->json($curriculum);
    }

    public function destroy(Curriculum $curriculum): JsonResponse
    {
        $curriculum->delete();
        return response()->json(null, 204);
    }

    public function syllabus(Curriculum $curriculum): JsonResponse
    {
        $syllabus = $curriculum->syllabus()
            ->with('faculty')
            ->orderBy('academic_year', 'desc')
            ->orderBy('semester', 'desc')
            ->get();

        return response()->json($syllabus);
    }

    public function storeSyllabus(Request $request, Curriculum $curriculum): JsonResponse
    {
        $validated = $request->validate([
            'faculty_id' => 'nullable|exists:faculty,id',
            'academic_year' => 'required|string|max:20',
            'semester' => 'required|string|max:50',
            'course_description' => 'required|string',
            'learning_objectives' => 'required|string',
            'topics_covered' => 'required|string',
            'grading_system' => 'required|string',
            'class_policies' => 'required|string',
            'required_materials' => 'required|string',
            'schedule_details' => 'required|string',
            'status' => 'required|in:Draft,Approved,Active,Archived',
        ]);

        $syllabus = $curriculum->syllabus()->create($validated);

        return response()->json($syllabus, 201);
    }

    public function lessons(Curriculum $curriculum): JsonResponse
    {
        $lessons = $curriculum->lessons()
            ->with('faculty')
            ->orderBy('lesson_number')
            ->get();

        return response()->json($lessons);
    }

    public function storeLesson(Request $request, Curriculum $curriculum): JsonResponse
    {
        $validated = $request->validate([
            'faculty_id' => 'nullable|exists:faculty,id',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'content' => 'required|string',
            'lesson_number' => 'required|integer|min:1',
            'week_number' => 'required|string|max:20',
            'learning_objectives' => 'nullable|array',
            'activities' => 'nullable|array',
            'assessment' => 'nullable|array',
            'resources' => 'nullable|array',
            'status' => 'required|in:Draft,Published,Archived',
        ]);

        $lesson = $curriculum->lessons()->create($validated);

        return response()->json($lesson, 201);
    }
}
