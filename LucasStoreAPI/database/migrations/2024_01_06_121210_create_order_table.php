<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrderTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->uuid('id')->primary()->index()->comment('UUID generated automatically in code');
            $table->foreignUuid('user_id')->constrained();
            $table->foreignUuid('ticket_id')->nullable()->constrained();
            $table->string('name_receiver', 100);
            $table->string('phone_receiver', 15);
            $table->string('address_receiver');
            $table->float('total_money');
            $table->string('note')->nullable();
            $table->enum('action', [
                'active',
                'inactive',
                'pending',
            ])->default('pending');
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
        Schema::dropIfExists('order');
    }
}
