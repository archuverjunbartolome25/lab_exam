<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\StudentController;
use App\Http\Controllers\API\FacultyController;
use App\Http\Controllers\API\InstructionController;
use App\Http\Controllers\API\ScheduleController;
use App\Http\Controllers\API\EventController;
use App\Http\Controllers\API\SearchController;
use App\Http\Controllers\API\DashboardController;

// Public routes
Route::get('dashboard/stats', [DashboardController::class, 'stats']);

Route::middleware('auth:sanctum')->group(function () {
    
    // Student Information Routes
    Route::apiResource('students', StudentController::class);
    Route::get('students/{student}/courses', [StudentController::class, 'courses']);
    Route::get('students/{student}/grades', [StudentController::class, 'grades']);
    
    // Faculty Information Routes
    Route::apiResource('faculty', FacultyController::class);
    Route::get('faculty/{faculty}/courses', [FacultyController::class, 'courses']);
    Route::get('faculty/{faculty}/schedule', [FacultyController::class, 'schedule']);
    
    // Instruction Routes
    Route::apiResource('curriculum', InstructionController::class);
    Route::get('curriculum/{curriculum}/syllabus', [InstructionController::class, 'syllabus']);
    Route::get('curriculum/{curriculum}/lessons', [InstructionController::class, 'lessons']);
    Route::post('curriculum/{curriculum}/syllabus', [InstructionController::class, 'storeSyllabus']);
    Route::post('curriculum/{curriculum}/lessons', [InstructionController::class, 'storeLesson']);
    
    // Scheduling Routes
    Route::apiResource('schedules', ScheduleController::class);
    Route::get('schedules/room/{room}', [ScheduleController::class, 'byRoom']);
    Route::get('schedules/faculty/{faculty}', [ScheduleController::class, 'byFaculty']);
    Route::get('schedules/section/{section}', [ScheduleController::class, 'bySection']);
    Route::get('schedules/semester/{semester}', [ScheduleController::class, 'bySemester']);
    
    // Events Routes
    Route::apiResource('events', EventController::class);
    Route::get('events/curricular', [EventController::class, 'curricular']);
    Route::get('events/extracurricular', [EventController::class, 'extracurricular']);
    Route::get('events/upcoming', [EventController::class, 'upcoming']);
    
    // Comprehensive Search/Filter Routes
    Route::get('search', [SearchController::class, 'search']);
    Route::get('search/students', [SearchController::class, 'searchStudents']);
    Route::get('search/faculty', [SearchController::class, 'searchFaculty']);
    Route::get('search/courses', [SearchController::class, 'searchCourses']);
    Route::get('search/events', [SearchController::class, 'searchEvents']);
});
