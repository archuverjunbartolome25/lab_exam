<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('academic_trackers', function (Blueprint $table) {
            $table->string('tracker_id')->primary();
            $table->integer('student_number');
            $table->string('course_code');
            $table->string('semester');
            $table->string('grade');
            $table->enum('status', ['Completed', 'In-progress', 'Dropped']);
            $table->timestamps();
            
            $table->foreign('student_number')->references('stud_num')->on('students')->onDelete('cascade');
            $table->foreign('course_code')->references('course_code')->on('courses')->onDelete('cascade');
            $table->index('tracker_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('academic_trackers');
    }
};
