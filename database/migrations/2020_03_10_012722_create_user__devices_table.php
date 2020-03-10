<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserDevicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user__devices', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->text("device_id");
            $table->text("device_name")->nullable(true);
            $table->text("device_description")->nullable(true);
            $table->integer("user_id")->unsigned();
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
        Schema::dropIfExists('user__devices');
    }
}
