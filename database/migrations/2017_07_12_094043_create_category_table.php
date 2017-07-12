<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCategoryTable extends Migration
{
    public function up()
    {
        Schema::create('category', function(Blueprint $table) {
            $table->increments('id');
            $table->string('name', 255);
            $table->string('name_en', 255)->nullable();
            $table->unsignedTinyInteger('status')->default(0);
            $table->unsignedInteger('parent_id')->nullable();
            $table->unsignedInteger('order')->default(1);
            $table->string('slug', 255);
            $table->string('slug_en', 255)->nullable();
            $table->string('code', 20);
            $table->dateTime('created_at');
            $table->unsignedTinyInteger('parent_status')->default(1);
        });
    }

    public function down()
    {
        Schema::dropIfExists('category');
    }
}
