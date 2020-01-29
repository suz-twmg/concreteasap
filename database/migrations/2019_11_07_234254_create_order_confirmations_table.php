<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrderConfirmationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_confirmations', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer("order_id")->unsigned();
            $table->double("message_quantity");
            $table->double("message_total");
            $table->double("quantity");
            $table->double("total");
            $table->boolean("rep_confirmation")->default(false);
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
        Schema::dropIfExists('order_confirmations');
    }
}
