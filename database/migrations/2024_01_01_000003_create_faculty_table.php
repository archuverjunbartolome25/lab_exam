<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('faculty', function (Blueprint $table) {
            $table->id('faculty_id');
            $table->string('fname');
            $table->string('lname');
            $table->string('department'); // Example: CCS
            $table->enum('employment_type', ['Full-time', 'Part-time']);
            $table->string('email')->unique();
            $table->integer('number');
            $table->timestamps();
            
            $table->index('email');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('faculty');
    }
};
