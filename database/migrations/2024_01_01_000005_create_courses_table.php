<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('courses', function (Blueprint $table) {
            $table->string('course_code')->primary();
            $table->string('course_title');
            $table->integer('Units');
            $table->string('Department');
            $table->text('course_description')->nullable();
            $table->timestamps();
            
            $table->index('course_code');
            $table->index('Department');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('courses');
    }
};
