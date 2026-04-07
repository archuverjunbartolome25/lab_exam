<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Laravel\Fortify\Features;
use App\Http\Controllers\FacultyController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\StudentQueryController;

Route::get('/', function () {
    return Inertia::render('Portal');
})->name('home');

Route::get('dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// Student Routes
Route::prefix('students')->name('students.')->group(function () {
    Route::get('/', [StudentController::class, 'index'])->name('index');
    Route::get('/{student}', [StudentController::class, 'show'])->name('show');
    Route::get('/query', [StudentController::class, 'query'])->name('query');
    Route::post('/query/run', [StudentController::class, 'runQuery'])->name('query.run');
    Route::post('/query/save', [StudentController::class, 'saveQuery'])->name('query.save');
});

// Student Query Routes
Route::prefix('query')->name('query.')->group(function () {
    Route::get('/skill', [StudentQueryController::class, 'queryBySkill'])->name('skill');
    Route::get('/affiliation', [StudentQueryController::class, 'queryByAffiliation'])->name('affiliation');
    Route::get('/advanced', [StudentQueryController::class, 'advancedQuery'])->name('advanced');
    Route::post('/search', [StudentQueryController::class, 'search'])->name('search');
});

// Faculty Routes (View Only)
Route::prefix('faculty')->name('faculty.')->group(function () {
    Route::get('/dashboard', [FacultyController::class, 'dashboard'])->name('dashboard');
    Route::get('/students', [FacultyController::class, 'students'])->name('students');
    Route::get('/students/create', [FacultyController::class, 'createStudent'])->name('students.create');
    Route::post('/students/create', [FacultyController::class, 'storeStudent'])->name('students.store');
    Route::get('/students/{student}', [FacultyController::class, 'showStudent'])->name('students.show');
    Route::put('/students/{student}', [FacultyController::class, 'editStudent'])->name('students.update');
    
    // Student data management routes
    Route::post('/students/{student}/courses', [FacultyController::class, 'addCourse'])->name('students.courses.store');
    Route::delete('/students/{student}/courses/{course}', [FacultyController::class, 'removeCourse'])->name('students.courses.destroy');
    
    Route::post('/students/{student}/achievements', [FacultyController::class, 'addAchievement'])->name('students.achievements.store');
    Route::delete('/students/{student}/achievements/{achievement}', [FacultyController::class, 'removeAchievement'])->name('students.achievements.destroy');
    
    Route::post('/students/{student}/skills', [FacultyController::class, 'addSkill'])->name('students.skills.store');
    Route::delete('/students/{student}/skills/{skill}', [FacultyController::class, 'removeSkill'])->name('students.skills.destroy');
    
    Route::post('/students/{student}/affiliations', [FacultyController::class, 'addAffiliation'])->name('students.affiliations.store');
    Route::delete('/students/{student}/affiliations/{affiliation}', [FacultyController::class, 'removeAffiliation'])->name('students.affiliations.destroy');
    
    Route::post('/students/{student}/violations', [FacultyController::class, 'addViolation'])->name('students.violations.store');
    Route::delete('/students/{student}/violations/{violation}', [FacultyController::class, 'removeViolation'])->name('students.violations.destroy');
    
    Route::post('/students/{student}/medical', [FacultyController::class, 'addMedicalRecord'])->name('students.medical.store');
    Route::delete('/students/{student}/medical/{medical}', [FacultyController::class, 'removeMedicalRecord'])->name('students.medical.destroy');
    
    Route::get('/sections', [FacultyController::class, 'sections'])->name('sections');
    Route::get('/courses', [FacultyController::class, 'courses'])->name('courses');
    Route::get('/grades', [FacultyController::class, 'grades'])->name('grades');
    Route::get('/reports', [FacultyController::class, 'reports'])->name('reports');
    Route::get('/settings', [FacultyController::class, 'settings'])->name('settings');
});

// Admin Routes
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/login', [App\Http\Controllers\Auth\AdminLoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [App\Http\Controllers\Auth\AdminLoginController::class, 'login']);
    Route::post('/logout', [App\Http\Controllers\Auth\AdminLoginController::class, 'logout'])->name('logout');
    Route::get('/dashboard', function () {
        return Inertia::render('Admin/Dashboard');
    })->middleware('auth:admin')->name('dashboard');
});

require __DIR__.'/settings.php';
