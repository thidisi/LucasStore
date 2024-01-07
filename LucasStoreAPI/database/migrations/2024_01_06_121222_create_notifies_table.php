<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNotifiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('notifies', function (Blueprint $table) {
            $table->uuid('id')->primary()->index()->comment('UUID generated automatically in code');
            $table->string('title', 150);
            $table->string('type', 50)->default('System');
            $table->text('products')->nullable();
            $table->text('discounts')->nullable();
            $table->text('orders')->nullable();
            $table->text('tickets')->nullable();
            $table->timestamps();
        });
        // Auto-incremented code with unique constraint
        \DB::statement('ALTER Table notifies add code INTEGER UNSIGNED NOT NULL UNIQUE AUTO_INCREMENT;');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('notifies');
    }
}
