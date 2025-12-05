<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id(); // Auto-incrementing primary key
            $table->string('name')->nullable(); // Name of the user (nullable for now)
            $table->string('job_id')->nullable(); // Job ID (nullable)
            $table->string('department')->nullable(); // Department (nullable)
            $table->string('designation')->nullable(); // Designation (nullable)
            $table->string('email')->unique(); // Unique email for the user
            $table->string('status')->nullable()->default('pending'); // Status, default 'pending'
            $table->timestamp('email_verified_at')->nullable(); // Email verification timestamp (nullable)
            $table->string('password'); // Hashed password
            $table->rememberToken(); // Remember token for "remember me" functionality (if applicable)
            $table->timestamps(); // Laravel timestamps (created_at, updated_at)
            $table->string('role')->default('user'); // Default role 'user'
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
