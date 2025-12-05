<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('leave_types', function (Blueprint $table) {
            $table->id();

            $table->string('name')->unique();
            $table->text('description')->nullable();

            // Rules / Policy fields
            $table->integer('max_days_per_year')->default(0);   // 0 = unlimited or admin-managed
            $table->boolean('carry_forward')->default(false);
            $table->boolean('requires_document')->default(false);

            // active = usable by users
            // inactive = hidden for users but kept internally
            $table->enum('status', ['active', 'inactive'])->default('active');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('leave_types');
    }
};
