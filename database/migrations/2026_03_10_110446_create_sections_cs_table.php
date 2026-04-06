<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('sections_cs', function (Blueprint $table) {
            $table->id('section_id');
            $table->string('section', 20); // e.g. 1CS-C
            $table->json('courses')->nullable(); // FK to courses_cs.course_id
            $table->unsignedBigInteger('faculty_id')->nullable();
            $table->string('schedule', 100)->nullable();
            $table->string('room', 50)->nullable();
            $table->integer('max_students')->default(40);
            $table->timestamps();
            
            $table->foreign('faculty_id')->references('faculty_id')->on('faculty')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sections_cs');
    }
};
