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
        Schema::create('medical_records', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('stud_id');
            $table->string('blood_type', 10);
            $table->string('allergies')->nullable();
            $table->string('medications')->nullable();
            $table->string('medical_conditions')->nullable();
            $table->string('emergency_contact_name');
            $table->string('emergency_contact_phone');
            $table->string('emergency_contact_relationship');
            $table->text('medical_history')->nullable();
            $table->string('physician_name')->nullable();
            $table->string('physician_phone')->nullable();
            $table->date('last_checkup')->nullable();
            $table->string('insurance_provider')->nullable();
            $table->string('insurance_policy_number')->nullable();
            $table->timestamps();
            
            $table->foreign('stud_id')->references('stud_id')->on('students')->onDelete('cascade');
            $table->index('stud_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('medical_records');
    }
};
