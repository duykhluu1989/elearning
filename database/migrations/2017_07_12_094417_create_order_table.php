<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrderTable extends Migration
{
    public function up()
    {
        Schema::create('order', function(Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('user_id');
            $table->string('number', 255)->nullable();
            $table->dateTime('created_at');
            $table->dateTime('cancelled_at')->nullable();
            $table->unsignedInteger('payment_method_id');
            $table->unsignedTinyInteger('payment_status')->default(0);
            $table->double('total_price')->unsigned();
            $table->double('total_discount_price')->unsigned();
            $table->double('total_point_price')->unsigned();
            $table->string('note', 255)->nullable();
        });
    }

    public function down()
    {
        Schema::dropIfExists('order');
    }
}
