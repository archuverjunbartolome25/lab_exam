<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('admin_acc', function (Blueprint $table) {
            $table->id('admin_acc_id');
            $table->unsignedBigInteger('admin_id')->unique();
            $table->string('email');
            $table->string('password');
            $table->boolean('is_active')->default(1);
            $table->timestamp('email_verified_at')->nullable();
            $table->string('two_factor_secret')->nullable();
            $table->text('two_factor_recovery_codes')->nullable();
            $table->timestamp('password_changed_at')->nullable();
            $table->timestamps();
            
            $table->foreign('admin_id')->references('admin_id')->on('admin')->onDelete('cascade');
            $table->index('email');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('admin_acc');
    }
};
