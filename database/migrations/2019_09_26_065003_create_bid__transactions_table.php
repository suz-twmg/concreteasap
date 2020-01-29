<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBidTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bid_transactions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('bid_id')->unsigned();
            $table->string("transaction_id");
            $table->string("invoice_url");
            $table->string("approved");
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
        Schema::dropIfExists('bid__transactions');
    }
}
