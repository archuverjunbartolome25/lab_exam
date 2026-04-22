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
            if (!Schema::hasColumn('students', 'course')) {
                $table->text('course')->nullable()->after('section_id');
            }
            if (!Schema::hasColumn('students', 'achievements')) {
                $table->text('achievements')->nullable()->after('course');
            }
            if (!Schema::hasColumn('students', 'skills')) {
                $table->text('skills')->nullable()->after('achievements');
            }
            if (!Schema::hasColumn('students', 'affiliations')) {
                $table->text('affiliations')->nullable()->after('skills');
            }
            if (!Schema::hasColumn('students', 'violations')) {
                $table->text('violations')->nullable()->after('affiliations');
            }
            if (!Schema::hasColumn('students', 'medicalRecord')) {
                $table->text('medicalRecord')->nullable()->after('violations');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('students', function (Blueprint $table) {
            // Drop added columns
            $table->dropColumn('course');
            $table->dropColumn('achievements');
            $table->dropColumn('skills');
            $table->dropColumn('affiliations');
            $table->dropColumn('violations');
            $table->dropColumn('medicalRecord');
        });
    }
};
