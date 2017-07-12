<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCategoryCourseTable extends Migration
{
    public function up()
    {
        Schema::create('category_course', function(Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('category_id');
            $table->unsignedInteger('course_id');
            $table->unsignedInteger('level')->default(1);
        });
    }

    public function down()
    {
        Schema::dropIfExists('category_course');
    }
}
