<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('students', function (Blueprint $table) {
            $table->id('stud_id');
            $table->integer('stud_num')->unique();
            $table->string('fname');
            $table->string('lname');
            $table->enum('gender', ['Male', 'Female']);
            $table->date('bday');
            $table->string('program'); // BSCS or BSIT
            $table->integer('year_level'); // 1-4 only
            $table->enum('enrollment_status', ['REGULAR', 'IRREGULAR']);
            $table->string('email')->unique();
            $table->integer('number')->nullable();
            $table->timestamps();
            
            $table->index('stud_num');
            $table->index('email');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};
