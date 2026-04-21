<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Add role column to users table
        Schema::table('users', function (Blueprint $table) {
            $table->enum('role', ['admin', 'faculty', 'student'])->default('student')->after('status');
        });

        // Migrate admin_users data to users table
        $adminUsers = DB::table('admin_users')->get();
        
        foreach ($adminUsers as $admin) {
            // Check if user already exists with this email
            $existingUser = DB::table('users')->where('email', $admin->email)->first();
            
            if (!$existingUser) {
                // Insert admin data into users table
                DB::table('users')->insert([
                    'user_num' => $admin->admin_num,
                    'fname' => $admin->fname,
                    'lname' => $admin->lname,
                    'email' => $admin->email,
                    'password' => $admin->password,
                    'status' => $admin->status,
                    'role' => 'admin',
                    'created_at' => $admin->created_at,
                    'updated_at' => $admin->updated_at,
                ]);
            } else {
                // Update existing user with admin role and admin_num
                DB::table('users')
                    ->where('email', $admin->email)
                    ->update([
                        'role' => 'admin',
                        'user_num' => $admin->admin_num,
                        'updated_at' => now(),
                    ]);
            }
        }

        // Drop the admin_users table
        Schema::dropIfExists('admin_users');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Recreate admin_users table
        Schema::create('admin_users', function (Blueprint $table) {
            $table->id('admin_user_id');
            $table->string('admin_num')->unique();
            $table->string('fname');
            $table->string('lname');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->rememberToken();
            $table->timestamps();
        });

        // Move admin users back to admin_users table
        $adminUsers = DB::table('users')->where('role', 'admin')->get();
        
        foreach ($adminUsers as $user) {
            DB::table('admin_users')->insert([
                'admin_num' => $user->user_num,
                'fname' => $user->fname,
                'lname' => $user->lname,
                'email' => $user->email,
                'password' => $user->password,
                'status' => $user->status,
                'created_at' => $user->created_at,
                'updated_at' => $user->updated_at,
            ]);
        }

        // Remove admin users from users table
        DB::table('users')->where('role', 'admin')->delete();

        // Drop role column from users table
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('role');
        });
    }
};
