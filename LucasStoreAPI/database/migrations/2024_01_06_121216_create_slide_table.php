<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSlideTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('slide', function (Blueprint $table) {
            $table->uuid('id')->primary()->index()->comment('UUID generated automatically in code');
            $table->string('title', 200);
            $table->string('slug');
            $table->text('image');
            $table->foreignUuid('major_category_id')->constrained();
            $table->enum('sort_order', [
                'slider',
                'instagram',
                'banner',
                'hide',
            ])->default('slider');
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
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('slide');
    }
}
