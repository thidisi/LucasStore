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
        Schema::create('users', function (Blueprint $table) {
            $table->uuid('id')->primary()->index()->comment('UUID generated automatically in code');

            // String columns
            $table->string('email', 100)->unique();
            $table->string('password', 100)->nullable();
            $table->string('name', 200)->nullable();
            $table->string('phone', 15)->unique()->nullable();
            $table->text('avatar', 255)->nullable();
            $table->date('birthday')->nullable();
            $table->enum('gender', [
                'male',
                'female',
            ]);

            // Foreign key
            $table->foreignId('role_id')->constrained()->onDelete('cascade');

            // Auto-incremented code with unique constraint
            $table->integer('code')->unsigned()->unique()->comment('Auto-incremented code');

            // Enum status with default value
            $table->enum('status', ['active', 'inactive', 'pending'])->default('active');

            $table->rememberToken();
            $table->timestamps();
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
