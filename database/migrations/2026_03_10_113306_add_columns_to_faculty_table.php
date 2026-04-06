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
        Schema::table('faculty', function (Blueprint $table) {
            $table->string('mname', 100)->nullable()->after('fname');
            $table->string('ext', 20)->nullable()->after('lname');
            $table->enum('position', ['Assistant Professor', 'Dean', 'Department Chair', 'Professor'])->nullable()->after('department');
            $table->enum('prog_dept_chair', ['BSIT', 'BSCS'])->nullable()->after('position');
            $table->json('classes')->nullable()->after('prog_dept_chair');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('faculty', function (Blueprint $table) {
            $table->dropColumn(['mname', 'ext', 'position', 'prog_dept_chair', 'classes']);
        });
    }
};
