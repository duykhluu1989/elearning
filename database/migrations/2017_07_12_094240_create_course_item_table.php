<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCourseItemTable extends Migration
{
    public function up()
    {
        Schema::create('course_item', function(Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('course_id');
            $table->string('name', 255);
            $table->string('name_en', 255)->nullable();
            $table->unsignedTinyInteger('type')->default(0);
            $table->text('content');
            $table->text('content_en')->nullable();
            $table->unsignedInteger('number')->default(1);
            $table->unsignedInteger('video_length')->nullable();
            $table->unsignedInteger('audio_length')->nullable();
        });
    }

    public function down()
    {
        Schema::dropIfExists('course_item');
    }
}
