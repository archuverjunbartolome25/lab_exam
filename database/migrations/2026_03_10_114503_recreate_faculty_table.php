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
        Schema::dropIfExists('faculty');
        
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
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('faculty');
    }
};
