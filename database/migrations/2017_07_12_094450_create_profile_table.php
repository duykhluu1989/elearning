<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProfileTable extends Migration
{
    public function up()
    {
        Schema::create('profile', function(Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('user_id');
            $table->string('first_name', 255)->nullable();
            $table->string('last_name', 255)->nullable();
            $table->unsignedTinyInteger('gender')->default(0);
            $table->date('birthday')->nullable();
            $table->string('phone', 20)->nullable();
            $table->string('address', 255)->nullable();
            $table->string('description', 1000)->nullable();
            $table->string('name', 255)->nullable();
            $table->string('title', 255)->nullable();
        });
    }

    public function down()
    {
        Schema::dropIfExists('profile');
    }
}
