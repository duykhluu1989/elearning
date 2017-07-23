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
            $table->unsignedTinyInteger('type')->default(0);
            $table->dateTime('created_at');
            $table->double('referral_commission_percent')->unsigned()->nullable();
            $table->double('referral_commission_amount')->unsigned()->nullable();
            $table->string('note', 255)->nullable();
        });
    }

    public function down()
    {
        Schema::dropIfExists('order_transaction');
    }
}
