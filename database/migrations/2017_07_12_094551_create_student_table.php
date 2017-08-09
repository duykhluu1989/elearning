<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStudentTable extends Migration
{
    public function up()
    {
        Schema::create('student', function(Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('user_id');
            $table->unsignedInteger('course_count')->default(0);
            $table->double('total_spent')->unsigned()->default(0);
            $table->double('current_point')->unsigned()->default(0);
            $table->double('total_point')->unsigned()->default(0);
            $table->unsignedInteger('finish_course_count')->unsigned()->default(0);
            $table->unsignedInteger('order_count')->default(0);
            $table->unsignedInteger('cancelled_order_count')->default(0);
        });
    }

    public function down()
    {
        Schema::dropIfExists('student');
    }
}
