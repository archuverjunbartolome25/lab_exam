<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->string('event_title');
            $table->enum('event_type', ['Curricular', 'Extracurricular']);
            $table->date('event_date');
            $table->time('start_time');
            $table->time('end_time');
            $table->string('location');
            $table->string('organizer');
            $table->timestamps();
            
            $table->foreign('organizer')->references('email')->on('faculty')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('events');
    }
};
