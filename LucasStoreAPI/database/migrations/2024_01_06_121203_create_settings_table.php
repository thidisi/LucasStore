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
        Schema::create('settings', function (Blueprint $table) {
            $table->uuid('id')->primary()->index()->comment('UUID generated automatically in code');
            $table->string('name', 100)->nullable()->unique();
            $table->enum('type', [
                'size',
                'color',
                'image',
            ]);
            $table->string('slug', 100)->nullable();
            $table->text('image_path')->nullable();
            $table->float('price_type')->nullable();
            $table->foreignUuid('production_id')->nullable()->constrained()->onDelete('cascade');
            $table->foreignUuid('parent_id')->nullable()->references('id')->on('settings')->onDelete('cascade');
            $table->enum('status', [
                'active',
                'inactive',
            ])->default('active');
            $table->timestamps();
            $table->softDeletes();
        });
        // Auto-incremented code with unique constraint
        \DB::statement('ALTER Table settings add code INTEGER UNSIGNED NOT NULL UNIQUE AUTO_INCREMENT;');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
};
