<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "=== DEBUG SECTION ASSIGNMENTS ===\n\n";

// Check sections
$sectionsIT = \App\Models\SectionsIT::all();
echo "IT Sections:\n";
foreach ($sectionsIT as $section) {
    echo "  {$section->section}: " . $section->courses . "\n";
}

echo "\nCS Sections:\n";
$sectionsCS = \App\Models\SectionsCS::all();
foreach ($sectionsCS as $section) {
    echo "  {$section->section}: " . $section->courses . "\n";
}

echo "\n=== STUDENT COURSES ===\n";
$students = \App\Models\Student::all();
foreach ($students as $student) {
    echo "  {$student->fname} {$student->lname} ({$student->program}): " . $student->courses . "\n";
}

echo "\n=== SECTION MATCHING TEST ===\n";
// Test matching for 1IT-A
$section1ITA = \App\Models\SectionsIT::where('section', '1IT-A')->first();
echo "\nTesting section 1IT-A (courses: {$section1ITA->courses}):\n";

$matchingStudents = \App\Models\Student::where('program', 'BSIT')
    ->whereJsonContains('courses', $section1ITA->courses)
    ->get();

echo "Matching students: " . $matchingStudents->count() . "\n";
foreach ($matchingStudents as $student) {
    echo "  - {$student->fname} {$student->lname}\n";
}

// Test with individual course
echo "\nTesting with individual course 1:\n";
$course1Students = \App\Models\Student::where('program', 'BSIT')
    ->whereJsonContains('courses', 1)
    ->get();

echo "Students with course 1: " . $course1Students->count() . "\n";
foreach ($course1Students as $student) {
    echo "  - {$student->fname} {$student->lname}\n";
}
