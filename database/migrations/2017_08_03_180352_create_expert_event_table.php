<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateExpertEventTable extends Migration
{
    public function up()
    {
        Schema::create('expert_event', function(Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('expert_id');
            $table->string('name', 255);
            $table->string('name_en', 255)->nullable();
            $table->string('url', 1000);
            $table->dateTime('created_at');
        });
    }

    public function down()
    {
        Schema::dropIfExists('expert_event');
    }
}
