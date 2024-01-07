<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateContactTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('contacts', function (Blueprint $table) {
            $table->uuid('id')->primary()->index()->comment('UUID generated automatically in code');
            $table->string('name');
            $table->string('email');
            $table->string('message');
            $table->enum('status', [
                'active',
                'inactive',
                'pending',
            ])->default('pending');
            $table->timestamps();
            $table->softDeletes();
        });
        // Auto-incremented code with unique constraint
        \DB::statement('ALTER Table contacts add code INTEGER UNSIGNED NOT NULL UNIQUE AUTO_INCREMENT;');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('contact');
    }
}
