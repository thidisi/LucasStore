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
        Schema::create('major_categories', function (Blueprint $table) {
            $table->uuid('id')->primary()->index()->comment('UUID generated automatically in code');
            $table->string('name', 100)->unique();
            $table->string('slug')->nullable();
            $table->enum('status', [
                'show',
                'hot_default',
                'hide',
            ])->default('show');
            $table->timestamps();
            $table->softDeletes();
        });
        \DB::statement('ALTER Table major_categories add code INTEGER UNSIGNED NOT NULL UNIQUE AUTO_INCREMENT;');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('major_categories');
    }
};
