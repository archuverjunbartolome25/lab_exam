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
            // Change guardian field from unsignedBigInteger to text
            $table->dropColumn('guardian');
            $table->text('guardian')->nullable()->after('contact_num');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('students', function (Blueprint $table) {
            // Revert guardian field back to unsignedBigInteger
            $table->dropColumn('guardian');
            $table->unsignedBigInteger('guardian')->nullable()->after('contact_num');
        });
    }
};
