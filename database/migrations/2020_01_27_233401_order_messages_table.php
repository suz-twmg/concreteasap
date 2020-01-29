<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class OrderMessagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('order_messages', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->double("quantity");
            $table->double("price");
            $table->string("status");
            $table->boolean("complete");
            $table->unsignedBigInteger("order_id");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
