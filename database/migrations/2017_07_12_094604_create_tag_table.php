<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTagTable extends Migration
{
    public function up()
    {
        Schema::create('tag', function(Blueprint $table) {
            $table->increments('id');
            $table->string('name', 255);
        });
    }

    public function down()
    {
        Schema::dropIfExists('tag');
    }
}
