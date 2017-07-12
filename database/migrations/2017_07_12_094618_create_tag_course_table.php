<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTagCourseTable extends Migration
{
    public function up()
    {
        Schema::create('tag_course', function(Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('tag_id');
            $table->unsignedInteger('course_id');
        });
    }

    public function down()
    {
        Schema::dropIfExists('tag_course');
    }
}
