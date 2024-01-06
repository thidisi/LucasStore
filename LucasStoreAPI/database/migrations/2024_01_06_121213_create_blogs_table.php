<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBlogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('blogs', function (Blueprint $table) {
            $table->uuid('id')->primary()->index()->comment('UUID generated automatically in code');
            $table->string('title', 200)->unique();
            $table->string('slug', 200)->nullable();
            $table->string('image', 255);
            $table->longtext('content');
            $table->integer('count_view')->default('0');
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
        Schema::dropIfExists('blogs');
    }
}
