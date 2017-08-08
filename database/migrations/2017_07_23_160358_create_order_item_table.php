<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrderItemTable extends Migration
{
    public function up()
    {
        Schema::create('order_item', function(Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('order_id');
            $table->unsignedInteger('course_id');
            $table->double('price')->unsigned();
            $table->double('point_price')->unsigned();
            $table->unsignedTinyInteger('referral_item')->default(0);
        });
    }

    public function down()
    {
        Schema::dropIfExists('order_item');
    }
}
