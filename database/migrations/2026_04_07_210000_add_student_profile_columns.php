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
            $table->json('achievements')->nullable()->after('courses');
            $table->json('skills')->nullable()->after('achievements');
            $table->json('affiliations')->nullable()->after('skills');
            $table->json('violations')->nullable()->after('affiliations');
            $table->json('medicalRecord')->nullable()->after('violations');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('students', function (Blueprint $table) {
            $table->dropColumn(['achievements', 'skills', 'affiliations', 'violations', 'medicalRecord']);
        });
    }
};
