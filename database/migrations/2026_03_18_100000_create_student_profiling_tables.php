<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('student_affiliations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('stud_id')->references('stud_id')->on('students')->onDelete('cascade');
            $table->string('type'); // club, organization, team, etc.
            $table->string('name');
            $table->string('role')->nullable(); // member, leader, captain, etc.
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->text('description')->nullable();
            $table->timestamps();
        });

        Schema::create('student_skills', function (Blueprint $table) {
            $table->id();
            $table->foreignId('stud_id')->references('stud_id')->on('students')->onDelete('cascade');
            $table->string('category'); // technical, soft, language, etc.
            $table->string('name');
            $table->string('level'); // beginner, intermediate, advanced, expert
            $table->text('description')->nullable();
            $table->date('acquired_date')->nullable();
            $table->string('certificate')->nullable(); // certification name
            $table->timestamps();
        });

        Schema::create('student_violations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('stud_id')->references('stud_id')->on('students')->onDelete('cascade');
            $table->string('type'); // academic, disciplinary, attendance
            $table->string('severity'); // minor, major, critical
            $table->text('description');
            $table->date('violation_date');
            $table->date('resolved_date')->nullable();
            $table->string('status'); // pending, resolved, appealed
            $table->string('sanction')->nullable(); // warning, suspension, etc.
            $table->timestamps();
        });

        Schema::create('student_achievements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('stud_id')->references('stud_id')->on('students')->onDelete('cascade');
            $table->string('type'); // academic, athletic, extracurricular
            $table->string('title');
            $table->text('description')->nullable();
            $table->date('achievement_date');
            $table->string('level')->nullable(); // school, district, national
            $table->string('awarder')->nullable(); // organization that gave award
            $table->timestamps();
        });

        Schema::create('academic_history', function (Blueprint $table) {
            $table->id();
            $table->foreignId('stud_id')->references('stud_id')->on('students')->onDelete('cascade');
            $table->foreignId('course_id')->nullable()->constrained('courses_it')->onDelete('set null');
            $table->string('course_code')->nullable();
            $table->string('course_name');
            $table->string('semester');
            $table->integer('year');
            $table->decimal('grade', 5, 2)->nullable();
            $table->decimal('credits', 5, 2);
            $table->string('grade_letter', 2)->nullable(); // A, B+, etc.
            $table->enum('status', ['completed', 'in_progress', 'dropped', 'withdrawn']);
            $table->timestamps();
        });

        Schema::create('saved_queries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('name');
            $table->text('description')->nullable();
            $table->json('criteria'); // search parameters
            $table->string('type'); // student_report, eligibility_check, etc.
            $table->boolean('is_public')->default(false);
            $table->timestamps();
        });

        Schema::create('query_history', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('query');
            $table->integer('result_count');
            $table->json('parameters');
            $table->timestamp('executed_at');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('query_history');
        Schema::dropIfExists('saved_queries');
        Schema::dropIfExists('academic_history');
        Schema::dropIfExists('student_achievements');
        Schema::dropIfExists('student_violations');
        Schema::dropIfExists('student_skills');
        Schema::dropIfExists('student_affiliations');
    }
};
