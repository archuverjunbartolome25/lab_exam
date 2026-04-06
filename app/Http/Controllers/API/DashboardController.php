<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\Faculty;
use App\Models\Curriculum;
use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class DashboardController extends Controller
{
    public function stats(): JsonResponse
    {
        $stats = [
            'totalStudents' => Student::count(),
            'totalFaculty' => Faculty::count(),
            'totalCourses' => Curriculum::count(),
            'totalEvents' => Event::count(),
        ];

        return response()->json($stats);
    }
}
