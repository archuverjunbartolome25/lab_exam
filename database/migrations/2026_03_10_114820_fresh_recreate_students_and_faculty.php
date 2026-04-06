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
        
        // Drop all related tables to avoid foreign key constraints
        Schema::dropIfExists('students_acc');
        Schema::dropIfExists('academic_trackers');
        Schema::dropIfExists('guidance_records');
        Schema::dropIfExists('medical_records');
        Schema::dropIfExists('students');
        Schema::dropIfExists('faculty_acc');
        Schema::dropIfExists('faculty');
        Schema::dropIfExists('events');
        
        // Create students table
        Schema::create('students', function (Blueprint $table) {
            $table->id('stud_id');
            $table->string('stud_num', 20)->unique();
            $table->string('fname', 100);
            $table->string('mname', 100)->nullable();
            $table->string('lname', 100);
            $table->string('ext', 20)->nullable();
            $table->enum('gender', ['Male', 'Female', 'Other']);
            $table->date('bday');
            $table->string('email', 150)->unique();
            $table->string('contact_num', 20);
            $table->text('address');
            $table->unsignedBigInteger('guardian')->nullable();
            $table->enum('program', ['BSIT', 'BSCS']);
            $table->string('program_code', 10);
            $table->json('courses')->nullable();
            $table->string('standing', 20); // 1st, 2nd, 3rd, 4th
            $table->enum('academic_status', ['Regular', 'Irregular']);
            $table->timestamps();
        });
        
        // Create faculty table
        Schema::create('faculty', function (Blueprint $table) {
            $table->id('faculty_id');
            $table->string('fname', 100);
            $table->string('mname', 100)->nullable();
            $table->string('lname', 100);
            $table->string('ext', 20)->nullable();
            $table->enum('position', ['Assistant Professor', 'Dean', 'Department Chair', 'Professor']);
            $table->enum('prog_dept_chair', ['BSIT', 'BSCS'])->nullable();
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
        Schema::dropIfExists('students');
        Schema::dropIfExists('faculty');
    }
};
