<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateExpertTable extends Migration
{
    public function up()
    {
        Schema::create('expert', function(Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('user_id');
            $table->unsignedTinyInteger('online')->default(0);
        });
    }

    public function down()
    {
        Schema::dropIfExists('expert');
    }
}
