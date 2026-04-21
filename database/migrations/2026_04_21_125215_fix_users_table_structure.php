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
        // Check if columns already exist and handle accordingly
        if (!Schema::hasColumn('users', 'user_num')) {
            Schema::table('users', function (Blueprint $table) {
                $table->string('user_num')->nullable()->after('id');
                $table->string('fname')->nullable()->after('user_num');
                $table->string('lname')->nullable()->after('fname');
                $table->enum('status', ['active', 'inactive'])->default('active')->after('password');
            });

            // Update existing records
            \DB::table('users')->get()->each(function ($user) {
                $userNum = 'USER' . str_pad($user->id, 4, '0', STR_PAD_LEFT);
                $nameParts = explode(' ', $user->name ?? '', 2);
                
                \DB::table('users')
                    ->where('id', $user->id)
                    ->update([
                        'user_num' => $userNum,
                        'fname' => $nameParts[0] ?? '',
                        'lname' => $nameParts[1] ?? '',
                    ]);
            });

            // Make columns not nullable and add unique constraint
            Schema::table('users', function (Blueprint $table) {
                $table->string('user_num')->unique()->change();
                $table->string('fname')->change();
                $table->string('lname')->change();
            });

            // Drop old name column if it exists
            if (Schema::hasColumn('users', 'name')) {
                Schema::table('users', function (Blueprint $table) {
                    $table->dropColumn('name');
                });
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Add back old column
            if (!Schema::hasColumn('users', 'name')) {
                $table->string('name')->after('id');
            }
            
            // Drop new columns if they exist
            if (Schema::hasColumn('users', 'user_num')) {
                $table->dropColumn(['user_num', 'fname', 'lname', 'status']);
            }
        });
    }
};
