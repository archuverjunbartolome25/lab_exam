<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('medical_records', function (Blueprint $table) {
            $table->string('record_id')->primary();
            $table->integer('student_number');
            $table->date('checkup_date');
            $table->integer('height_cm')->nullable();
            $table->float('weight_kg')->nullable();
            $table->text('medical_notes')->nullable();
            $table->timestamps();
            
            $table->foreign('student_number')->references('stud_num')->on('students')->onDelete('cascade');
            $table->index('record_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('medical_records');
    }
};
