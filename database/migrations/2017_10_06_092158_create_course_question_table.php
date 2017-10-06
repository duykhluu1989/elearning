<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCourseQuestionTable extends Migration
{
    public function up()
    {
        Schema::create('course_question', function(Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('user_id');
            $table->unsignedInteger('course_id');
            $table->string('question', 1000);
            $table->string('answer', 1000)->nullable();
            $table->dateTime('created_at');
            $table->unsignedTinyInteger('status')->default(0);
        });
    }

    public function down()
    {
        Schema::dropIfExists('course_question');
    }
}
