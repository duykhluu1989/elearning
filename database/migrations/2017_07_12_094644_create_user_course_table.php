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
            $table->unsignedInteger('course_item_tracking')->default(0);
            $table->unsignedInteger('order_id')->nullable();
            $table->unsignedTinyInteger('finish')->default(0);
        });
    }

    public function down()
    {
        Schema::dropIfExists('user_course');
    }
}
