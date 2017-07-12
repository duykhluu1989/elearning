<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserCourseTable extends Migration
{
    public function up()
    {
        Schema::create('user_course', function(Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('user_id');
            $table->unsignedInteger('course_id');
            $table->unsignedInteger('course_item_tracking');
            $table->unsignedInteger('last_course_item')->nullable();
            $table->unsignedInteger('order_id');
        });
    }

    public function down()
    {
        Schema::dropIfExists('user_course');
    }
}
