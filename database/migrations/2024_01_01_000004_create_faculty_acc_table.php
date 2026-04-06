<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('faculty_acc', function (Blueprint $table) {
            $table->id('faculty_acc_id');
            $table->unsignedBigInteger('faculty_id')->unique();
            $table->string('email');
            $table->string('password');
            $table->timestamps();
            
            $table->foreign('faculty_id')->references('faculty_id')->on('faculty')->onDelete('cascade');
            $table->index('email');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('faculty_acc');
    }
};
