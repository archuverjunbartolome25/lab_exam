<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sections', function (Blueprint $table) {
            $table->string('section_code')->primary();
            $table->string('course_code');
            $table->string('semester');
            $table->string('schedule_day'); // Monday-Saturday
            $table->time('start_time');
            $table->time('end_time');
            $table->integer('capacity');
            $table->timestamps();
            
            $table->foreign('course_code')->references('course_code')->on('courses')->onDelete('cascade');
            $table->index('section_code');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sections');
    }
};
