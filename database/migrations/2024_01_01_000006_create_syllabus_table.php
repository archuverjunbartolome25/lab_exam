<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('syllabus', function (Blueprint $table) {
            $table->id();
            $table->string('course_code');
            $table->text('description');
            $table->text('learning_outcomes');
            $table->text('grading_policy');
            $table->timestamps();
            
            $table->foreign('course_code')->references('course_code')->on('courses')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('syllabus');
    }
};
