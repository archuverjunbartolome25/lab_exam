<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Schedule;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\Rule;

class ScheduleController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $query = Schedule::with(['curriculum', 'faculty']);

        if ($request->has('search')) {
            $search = $request->get('search');
            $query->where(function ($q) use ($search) {
                $q->where('section', 'like', "%{$search}%")
                  ->orWhere('room', 'like', "%{$search}%")
                  ->orWhere('building', 'like', "%{$search}%")
                  ->orWhereHas('curriculum', function ($subQ) use ($search) {
                      $subQ->where('course_name', 'like', "%{$search}%")
                           ->orWhere('course_code', 'like', "%{$search}%");
                  });
            });
        }

        if ($request->has('semester')) {
            $query->where('semester', $request->get('semester'));
        }

        if ($request->has('academic_year')) {
            $query->where('academic_year', $request->get('academic_year'));
        }

        if ($request->has('day_of_week')) {
            $query->where('day_of_week', $request->get('day_of_week'));
        }

        $schedules = $query->orderBy('day_of_week')
                           ->orderBy('start_time')
                           ->paginate($request->get('per_page', 15));

        return response()->json($schedules);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'curriculum_id' => 'required|exists:curriculum,id',
            'faculty_id' => 'required|exists:faculty,id',
            'section' => 'required|string|max:50',
            'room' => 'required|string|max:50',
            'building' => 'required|string|max:100',
            'day_of_week' => 'required|in:Monday,Tuesday,Wednesday,Thursday,Friday,Saturday,Sunday',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'academic_year' => 'required|string|max:20',
            'semester' => 'required|string|max:50',
            'max_students' => 'required|integer|min:1',
            'current_students' => 'nullable|integer|min:0',
            'status' => 'required|in:Active,Cancelled,Completed',
            'notes' => 'nullable|string',
        ]);

        $schedule = Schedule::create($validated);

        return response()->json($schedule, 201);
    }

    public function show(Schedule $schedule): JsonResponse
    {
        $schedule->load(['curriculum', 'faculty', 'enrollments.student']);
        return response()->json($schedule);
    }

    public function update(Request $request, Schedule $schedule): JsonResponse
    {
        $validated = $request->validate([
            'curriculum_id' => 'required|exists:curriculum,id',
            'faculty_id' => 'required|exists:faculty,id',
            'section' => 'required|string|max:50',
            'room' => 'required|string|max:50',
            'building' => 'required|string|max:100',
            'day_of_week' => 'required|in:Monday,Tuesday,Wednesday,Thursday,Friday,Saturday,Sunday',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'academic_year' => 'required|string|max:20',
            'semester' => 'required|string|max:50',
            'max_students' => 'required|integer|min:1',
            'current_students' => 'nullable|integer|min:0',
            'status' => 'required|in:Active,Cancelled,Completed',
            'notes' => 'nullable|string',
        ]);

        $schedule->update($validated);

        return response()->json($schedule);
    }

    public function destroy(Schedule $schedule): JsonResponse
    {
        $schedule->delete();
        return response()->json(null, 204);
    }

    public function byRoom(Request $request, $room): JsonResponse
    {
        $schedules = Schedule::with(['curriculum', 'faculty'])
            ->byRoom($room)
            ->when($request->has('semester'), function ($query) use ($request) {
                $query->where('semester', $request->get('semester'));
            })
            ->when($request->has('academic_year'), function ($query) use ($request) {
                $query->where('academic_year', $request->get('academic_year'));
            })
            ->orderBy('day_of_week')
            ->orderBy('start_time')
            ->get();

        return response()->json($schedules);
    }

    public function byFaculty(Request $request, $facultyId): JsonResponse
    {
        $schedules = Schedule::with(['curriculum', 'faculty'])
            ->byFaculty($facultyId)
            ->when($request->has('semester'), function ($query) use ($request) {
                $query->where('semester', $request->get('semester'));
            })
            ->when($request->has('academic_year'), function ($query) use ($request) {
                $query->where('academic_year', $request->get('academic_year'));
            })
            ->orderBy('day_of_week')
            ->orderBy('start_time')
            ->get();

        return response()->json($schedules);
    }

    public function bySection(Request $request, $section): JsonResponse
    {
        $schedules = Schedule::with(['curriculum', 'faculty'])
            ->bySection($section)
            ->when($request->has('semester'), function ($query) use ($request) {
                $query->where('semester', $request->get('semester'));
            })
            ->when($request->has('academic_year'), function ($query) use ($request) {
                $query->where('academic_year', $request->get('academic_year'));
            })
            ->orderBy('day_of_week')
            ->orderBy('start_time')
            ->get();

        return response()->json($schedules);
    }

    public function bySemester(Request $request, $semester): JsonResponse
    {
        $schedules = Schedule::with(['curriculum', 'faculty'])
            ->bySemester($semester)
            ->when($request->has('academic_year'), function ($query) use ($request) {
                $query->where('academic_year', $request->get('academic_year'));
            })
            ->orderBy('day_of_week')
            ->orderBy('start_time')
            ->get();

        return response()->json($schedules);
    }
}
