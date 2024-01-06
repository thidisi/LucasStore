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
        Schema::create('categories', function (Blueprint $table) {
            $table->uuid('id')->primary()->index()->comment('UUID generated automatically in code');
            $table->foreignUuid('major_category_id')->constrained();
            $table->string('name')->unique();
            $table->text('slug');
            $table->text('avatar', 255)->nullable();
            $table->enum('status', [
                'active',
                'inactive',
            ])->default('active');
            $table->integer('code')->unsigned()->unique()->comment('Auto-incremented code');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('categories');
    }
};
