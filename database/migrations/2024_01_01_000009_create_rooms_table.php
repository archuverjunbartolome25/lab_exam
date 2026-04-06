<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('rooms', function (Blueprint $table) {
            $table->id();
            $table->string('room_name')->unique();
            $table->string('building');
            $table->enum('room_type', ['Lecture', 'Lab']);
            $table->integer('capacity');
            $table->timestamps();
            
            $table->index('room_name');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rooms');
    }
};
