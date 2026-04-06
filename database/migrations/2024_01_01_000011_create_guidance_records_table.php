<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('guidance_records', function (Blueprint $table) {
            $table->string('record_id')->primary();
            $table->integer('student_number');
            $table->string('counselor');
            $table->date('session_date');
            $table->text('topics_discussed')->nullable();
            $table->text('action_plan')->nullable();
            $table->timestamps();
            
            $table->foreign('student_number')->references('stud_num')->on('students')->onDelete('cascade');
            $table->index('record_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('guidance_records');
    }
};
