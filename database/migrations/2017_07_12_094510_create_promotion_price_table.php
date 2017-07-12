<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePromotionPriceTable extends Migration
{
    public function up()
    {
        Schema::create('promotion_price', function(Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('course_id');
            $table->unsignedTinyInteger('status')->default(0);
            $table->dateTime('start_time');
            $table->dateTime('end_time');
            $table->double('price')->unsigned()->default(0);
        });
    }

    public function down()
    {
        Schema::dropIfExists('promotion_price');
    }
}
