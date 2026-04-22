<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('students', function (Blueprint $table) {
            // Just drop and recreate the enum with correct values
            $table->dropColumn('academic_status');
            $table->enum('academic_status', ['Enrolled', 'Not Enrolled', 'On Leave', 'Graduated', 'Dropped'])->default('Enrolled')->after('standing');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('students', function (Blueprint $table) {
            // Revert academic_status enum to original values
            $table->dropColumn('academic_status');
            $table->enum('academic_status', ['Regular', 'Irregular'])->default('Regular')->after('standing');
        });
    }
};
