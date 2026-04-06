<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('admin', function (Blueprint $table) {
            $table->id('admin_id');
            $table->string('admin_num')->unique();
            $table->string('fname');
            $table->string('lname');
            $table->string('email')->unique();
            $table->string('phone')->nullable();
            $table->enum('role', ['Super Admin', 'Admin', 'Moderator']);
            $table->enum('status', ['Active', 'Inactive', 'Suspended']);
            $table->string('department')->nullable();
            $table->text('permissions')->nullable();
            $table->timestamp('last_login')->nullable();
            $table->timestamps();
            
            $table->index('admin_num');
            $table->index('email');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('admin');
    }
};
