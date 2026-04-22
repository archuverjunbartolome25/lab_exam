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
            // First, update existing records to have proper stud_num values
            DB::statement("UPDATE students SET stud_num = CONCAT('STU', YEAR(CURRENT_DATE), LPAD(stud_id, 4, '0')) WHERE stud_num = '' OR stud_num IS NULL");
            
            // Fix field length constraints to prevent data truncation
            $table->string('stud_num', 50)->change();  // Increased from 20 to 50
            $table->string('program_code', 20)->change();  // Increased from 10 to 20
            $table->string('standing', 50)->change();  // Increased from 20 to 50
            $table->string('contact_num', 50)->change();  // Increased from 20 to 50
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('students', function (Blueprint $table) {
            // Revert field length constraints to original values
            $table->string('stud_num', 20)->change();  // Back to 20
            $table->string('program_code', 10)->change();  // Back to 10
            $table->string('standing', 20)->change();  // Back to 20
            $table->string('contact_num', 20)->change();  // Back to 20
        });
    }
};
