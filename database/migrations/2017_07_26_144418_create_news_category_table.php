<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNewsCategoryTable extends Migration
{
    public function up()
    {
        Schema::create('news_category', function(Blueprint $table) {
            $table->increments('id');
            $table->string('name', 255);
            $table->string('name_en', 255)->nullable();
            $table->unsignedTinyInteger('status')->default(0);
            $table->unsignedInteger('order')->default(1);
            $table->string('slug', 255);
            $table->string('slug_en', 255)->nullable();
            $table->dateTime('created_at');
            $table->text('rss')->nullable();
            $table->string('image', 1000)->nullable();
        });
    }

    public function down()
    {
        Schema::dropIfExists('news_category');
    }
}
