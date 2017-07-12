<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePaymentMethodTable extends Migration
{
    public function up()
    {
        Schema::create('payment_method', function(Blueprint $table) {
            $table->increments('id');
            $table->string('name', 255);
            $table->string('name_en', 255)->nullable();
            $table->unsignedTinyInteger('status')->default(0);
            $table->unsignedTinyInteger('type')->default(0);
            $table->string('code', 40);
            $table->text('detail')->nullable();
            $table->unsignedInteger('order')->default(1);
        });
    }

    public function down()
    {
        Schema::dropIfExists('payment_method');
    }
}
