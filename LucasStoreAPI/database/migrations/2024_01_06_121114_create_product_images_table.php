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
        Schema::create('product_images', function (Blueprint $table) {
            $table->uuid('id')->primary()->index()->comment('UUID generated automatically in code');
            $table->foreignUuid('production_id')->constrained();
            $table->text('image');
            $table->enum('status', [
                'active',
                'inactive',
            ])->default('active');
            $table->timestamps();
            $table->softDeletes();
        });
        // Auto-incremented code with unique constraint
        \DB::statement('ALTER Table product_images add code INTEGER UNSIGNED NOT NULL UNIQUE AUTO_INCREMENT;');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_images');
    }
};
