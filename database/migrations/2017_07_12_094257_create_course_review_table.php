<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCourseReviewTable extends Migration
{
    public function up()
    {
        Schema::create('course_review', function(Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('user_id');
            $table->unsignedInteger('course_id');
            $table->string('detail', 1000);
        });
    }

    public function down()
    {
        Schema::dropIfExists('course_review');
    }
}
