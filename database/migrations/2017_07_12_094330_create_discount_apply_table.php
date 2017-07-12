<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDiscountApplyTable extends Migration
{
    public function up()
    {
        Schema::create('discount_apply', function(Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('discount_id');
            $table->unsignedInteger('apply_id');
            $table->string('target', 255);
        });
    }

    public function down()
    {
        Schema::dropIfExists('discount_apply');
    }
}
