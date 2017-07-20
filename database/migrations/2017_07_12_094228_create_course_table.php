<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCourseTable extends Migration
{
    public function up()
    {
        Schema::create('course', function(Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('user_id');
            $table->string('name', 255);
            $table->string('name_en', 255)->nullable();
            $table->double('price')->unsigned()->default(0);
            $table->unsignedTinyInteger('status')->default(0);
            $table->text('description');
            $table->text('description_en')->nullable();
            $table->double('point_price')->unsigned()->nullable();
            $table->string('slug', 255);
            $table->string('slug_en', 255)->nullable();
            $table->string('code', 40);
            $table->unsignedInteger('video_length')->nullable();
            $table->unsignedInteger('level_id')->nullable();
            $table->string('short_description', 1000)->nullable();
            $table->string('short_description_en', 1000)->nullable();
            $table->unsignedTinyInteger('highlight')->default(0);
            $table->dateTime('published_at')->nullable();
            $table->string('image', 1000)->nullable();
            $table->unsignedInteger('item_count')->default(0);
            $table->dateTime('created_at');
            $table->unsignedInteger('bought_count')->default(0);
            $table->unsignedInteger('view_count')->default(0);
            $table->unsignedTinyInteger('category_status')->default(0);
            $table->unsignedInteger('audio_length')->nullable();
            $table->unsignedInteger('category_id')->nullable();
        });
    }

    public function down()
    {
        Schema::dropIfExists('course');
    }
}
