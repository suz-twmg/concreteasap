<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrderConcretesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_concretes', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string("suburb");
            $table->string("post_code");
            $table->string("state");
            $table->string("type");
            $table->string("placement_type");
            $table->string("mpa");
            $table->string("agg");
            $table->string("slump");
            $table->string("acc");
            $table->integer("quantity");
            $table->date("delivery_date");
            $table->date("delivery_date1");
            $table->date("delivery_date2");
            $table->time('time_preference1');
            $table->time('time_preference2');
            $table->time('time_preference3');
            $table->string('time_deliveries');
            $table->string("urgency");
            $table->boolean("message_required");
            $table->enum("preference",["On Site","On Call"]);
            $table->string("colours");
            $table->text("delivery_instructions");
            $table->text("special_instructions");
            $table->text("address");
            $table->unsignedBigInteger("order_id");
            $table->foreign('order_id')->references('id')->on('orders');
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
        Schema::dropIfExists('order_concretes');
    }
}
