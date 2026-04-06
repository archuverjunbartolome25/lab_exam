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
        Schema::table('sections_it', function (Blueprint $table) {
            $table->unsignedBigInteger('stud_id')->nullable()->after('courses');
            $table->foreign('stud_id')->references('stud_id')->on('students')->onDelete('cascade');
        });

        Schema::table('sections_cs', function (Blueprint $table) {
            $table->unsignedBigInteger('stud_id')->nullable()->after('courses');
            $table->foreign('stud_id')->references('stud_id')->on('students')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sections_it', function (Blueprint $table) {
            $table->dropForeign(['stud_id']);
            $table->dropColumn('stud_id');
        });

        Schema::table('sections_cs', function (Blueprint $table) {
            $table->dropForeign(['stud_id']);
            $table->dropColumn('stud_id');
        });
    }
};
