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
            $table->string('mname', 100)->nullable()->after('fname');
            $table->string('ext', 20)->nullable()->after('lname');
            $table->string('contact_num', 20)->nullable()->after('email');
            $table->text('address')->nullable()->after('contact_num');
            $table->string('guardian_table', 20)->nullable()->after('address');
            $table->unsignedBigInteger('guardian')->nullable()->after('guardian_table');
            $table->string('program_code', 10)->nullable()->after('program');
            $table->json('courses')->nullable()->after('program_code');
            $table->string('standing', 20)->nullable()->after('courses');
            $table->enum('academic_status', ['Regular', 'Irregular'])->nullable()->after('standing');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('students', function (Blueprint $table) {
            $table->dropColumn(['mname', 'ext', 'contact_num', 'address', 'guardian_table', 'guardian', 'program_code', 'courses', 'standing', 'academic_status']);
        });
    }
};
