<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReoProducts extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('reo_products', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string("name");
            $table->string("description");
            $table->unsignedBigInteger("category_id");
            $table->foreign('category_id')->references('id')->on('reo_categories');
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
        Schema::dropIfExists('reo_products');
    }
}
