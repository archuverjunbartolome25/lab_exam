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
            $table->string('guardian_table', 20)->nullable(); // 'guardians_it' or 'guardians_cs'
            $table->unsignedBigInteger('guardian')->nullable();
            $table->enum('program', ['BSIT', 'BSCS']);
            $table->string('program_code', 10);
            $table->json('courses')->nullable();
            $table->string('standing', 20); // 1st, 2nd, 3rd, 4th
            $table->enum('academic_status', ['Regular', 'Irregular']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};
