<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrderTransactionTable extends Migration
{
    public function up()
    {
        Schema::create('order_transaction', function(Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('order_id');
            $table->double('amount')->unsigned();
            $table->double('point_amount')->unsigned();
            $table->unsignedTinyInteger('type')->default(0);
            $table->dateTime('created_at');
            $table->string('note', 255)->nullable();
            $table->text('detail')->nullable();
        });
    }

    public function down()
    {
        Schema::dropIfExists('order_transaction');
    }
}
