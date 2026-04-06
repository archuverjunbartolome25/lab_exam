<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Disable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        
        // Drop existing tables
        Schema::dropIfExists('courses_it');
        Schema::dropIfExists('sections_it');
        Schema::dropIfExists('courses_cs');
        Schema::dropIfExists('sections_cs');
        
        // Create courses_it table
        Schema::create('courses_it', function (Blueprint $table) {
            $table->id('course_id');
            $table->string('course', 200);
            $table->string('course_code', 20)->unique();
            $table->integer('credits')->default(3);
            $table->timestamps();
        });
        
        // Create sections_it table
        Schema::create('sections_it', function (Blueprint $table) {
            $table->id('section_id');
            $table->string('section', 20); // e.g. 1IT-C
            $table->json('courses')->nullable(); // FK to courses_it.course_id
            $table->integer('num_of_stud')->default(0);
            $table->timestamps();
        });
        
        // Create courses_cs table
        Schema::create('courses_cs', function (Blueprint $table) {
            $table->id('course_id');
            $table->string('course', 200);
            $table->string('course_code', 20)->unique();
            $table->integer('credits')->default(3);
            $table->timestamps();
        });
        
        // Create sections_cs table
        Schema::create('sections_cs', function (Blueprint $table) {
            $table->id('section_id');
            $table->string('section', 20); // e.g. 1CS-C
            $table->json('courses')->nullable(); // FK to courses_cs.course_id
            $table->integer('num_of_stud')->default(0);
            $table->timestamps();
        });
        
        // Re-enable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('courses_it');
        Schema::dropIfExists('sections_it');
        Schema::dropIfExists('courses_cs');
        Schema::dropIfExists('sections_cs');
    }
};
