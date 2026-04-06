<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('students_acc', function (Blueprint $table) {
            $table->id('stud_acc_id');
            $table->unsignedBigInteger('stud_id')->unique();
            $table->boolean('is_active')->default(1);
            $table->string('email');
            $table->string('password');
            $table->timestamps();
            
            $table->foreign('stud_id')->references('stud_id')->on('students')->onDelete('cascade');
            $table->index('email');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('students_acc');
    }
};
