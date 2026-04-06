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
        Schema::create('guardians_cs', function (Blueprint $table) {
            $table->id('guardian_id');
            $table->string('fname', 100);
            $table->string('mname', 100)->nullable();
            $table->string('lname', 100);
            $table->string('relationship', 50);
            $table->string('contact_num', 20);
            $table->text('address');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('guardians_cs');
    }
};
