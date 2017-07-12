<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLevelTable extends Migration
{
    public function up()
    {
        Schema::create('level', function(Blueprint $table) {
            $table->increments('id');
            $table->string('name', 255);
            $table->string('name_en', 255)->nullable();
            $table->unsignedInteger('order')->default(1);
        });
    }

    public function down()
    {
        Schema::dropIfExists('level');
    }
}
