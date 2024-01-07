<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAttributesValuesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('attribute_values', function (Blueprint $table) {
            $table->uuid('id')->primary()->index()->comment('UUID generated automatically in code');
            $table->foreignId('attribute_id')->constrained();
            $table->string('name',100)->unique();
            $table->string('slug');
            $table->string('descriptions')->nullable();
            $table->enum('status', [
                'active',
                'inactive',
            ])->default('active');
            $table->timestamps();
            $table->softDeletes();
        });
        // Auto-incremented code with unique constraint
        \DB::statement('ALTER Table attribute_values add code INTEGER UNSIGNED NOT NULL UNIQUE AUTO_INCREMENT;');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('attributes_values');
    }
}
