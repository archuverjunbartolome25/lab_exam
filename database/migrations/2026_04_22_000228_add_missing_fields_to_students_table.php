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
        Schema::table('students', function (Blueprint $table) {
            // Add missing fields that the API expects
            $table->string('program_code', 10)->after('program');
            $table->text('course')->nullable()->after('section_id');
            $table->text('achievements')->nullable()->after('course');
            $table->text('skills')->nullable()->after('achievements');
            $table->text('affiliations')->nullable()->after('skills');
            $table->text('violations')->nullable()->after('affiliations');
            $table->text('medicalRecord')->nullable()->after('violations');
            
            // Update guardian field to be text instead of big integer
            $table->dropColumn('guardian');
            $table->text('guardian')->after('contact_num');
            
            // Update academic_status enum to match API expectations
            $table->dropColumn('academic_status');
            $table->enum('academic_status', ['Enrolled', 'Not Enrolled', 'On Leave', 'Graduated', 'Dropped'])->after('standing');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('students', function (Blueprint $table) {
            // Drop added columns
            $table->dropColumn('program_code');
            $table->dropColumn('course');
            $table->dropColumn('achievements');
            $table->dropColumn('skills');
            $table->dropColumn('affiliations');
            $table->dropColumn('violations');
            $table->dropColumn('medicalRecord');
            
            // Revert guardian back to big integer
            $table->dropColumn('guardian');
            $table->unsignedBigInteger('guardian')->nullable();
            
            // Revert academic_status back to original enum
            $table->dropColumn('academic_status');
            $table->enum('academic_status', ['Regular', 'Irregular']);
        });
    }
};
