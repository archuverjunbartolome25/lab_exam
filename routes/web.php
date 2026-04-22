<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Laravel\Fortify\Features;
use App\Http\Controllers\FacultyController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\StudentQueryController;

Route::get('/', function () {
    return redirect('/login');
})->name('home');

// =====================================================
// FACULTY ROUTES — view-only, uses default 'web' guard
// =====================================================
Route::prefix('faculty')->name('faculty.')->middleware(['auth'])->group(function () {
    Route::redirect('/', '/faculty/dashboard');

    Route::get('/dashboard', [FacultyController::class, 'dashboard'])->name('dashboard');
    Route::get('/students', [FacultyController::class, 'students'])->name('students');
    Route::get('/students/{student}', [FacultyController::class, 'showStudent'])->name('students.show');
    Route::get('/sections', [FacultyController::class, 'sections'])->name('sections');
    Route::get('/courses', [FacultyController::class, 'courses'])->name('courses');
    Route::get('/grades', [FacultyController::class, 'grades'])->name('grades');
    Route::get('/reports', [FacultyController::class, 'reports'])->name('reports');
    Route::get('/settings', [FacultyController::class, 'settings'])->name('settings');
    Route::get('/users/{id}', [FacultyController::class, 'showUser'])->name('users.show');
});

// =====================================================
// ADMIN ROUTES — full CRUD, uses 'admin' guard
// =====================================================
Route::prefix('admin')->name('admin.')->group(function () {
    // Auth (no guard needed)
    Route::get('/login', [App\Http\Controllers\Auth\AdminLoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [App\Http\Controllers\Auth\AdminLoginController::class, 'login']);
    Route::post('/logout', [App\Http\Controllers\Auth\AdminLoginController::class, 'logout'])->name('logout');

    // Admin Routes (No Authentication Required)
    Route::group([], function () {
        Route::redirect('/', '/admin/dashboard');

        Route::get('/dashboard', function () {
            return Inertia::render('AdminDashboard');
        })->name('dashboard');
        Route::get('/users', [FacultyController::class, 'users'])->name('users');
        Route::get('/users/create', function () {
            return Inertia::render('AdminCreateUser');
        })->name('users.create');
        Route::post('/users/create', [FacultyController::class, 'storeUser'])->name('users.store');
        Route::get('/users/{id}/edit', [FacultyController::class, 'editUser'])->name('users.edit');
        Route::put('/users/{id}', [FacultyController::class, 'updateUser'])->name('users.update');
        Route::delete('/users/{id}', [FacultyController::class, 'deleteUser'])->name('users.delete');
        Route::get('/faculty', [FacultyController::class, 'sections'])->name('faculty');
        Route::get('/students', [FacultyController::class, 'students'])->name('students');
        Route::get('/students/create', function () {
            return Inertia::render('AdminCreateStudent');
        })->name('students.create');
        Route::post('/students/create', [FacultyController::class, 'storeStudent'])->name('students.store');
        Route::get('/students/{student}', [FacultyController::class, 'showStudent'])->name('students.show');
        Route::put('/students/{student}', [FacultyController::class, 'editStudent'])->name('students.update');
        Route::get('/sections', [FacultyController::class, 'sections'])->name('sections');
        Route::get('/courses', [FacultyController::class, 'courses'])->name('courses');
        Route::get('/grades', [FacultyController::class, 'grades'])->name('grades');
        Route::get('/schedules', function () {
            return Inertia::render('AdminSchedules');
        })->name('schedules');
        Route::get('/events', function () {
            return Inertia::render('AdminEvents');
        })->name('events');
        Route::get('/reports', function () {
            return Inertia::render('AdminReports');
        })->name('reports');
        Route::get('/settings', [FacultyController::class, 'settings'])->name('settings');
        Route::get('/users/{id}', [FacultyController::class, 'showUser'])->name('users.show');

        // Student data management (admin only)
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
    });
});

// Student Query Routes (shared)
Route::prefix('query')->name('query.')->middleware(['auth'])->group(function () {
    Route::get('/skill', [StudentQueryController::class, 'queryBySkill'])->name('skill');
    Route::get('/affiliation', [StudentQueryController::class, 'queryByAffiliation'])->name('affiliation');
    Route::get('/advanced', [StudentQueryController::class, 'advancedQuery'])->name('advanced');
    Route::post('/search', [StudentQueryController::class, 'search'])->name('search');
});

require __DIR__.'/settings.php';
