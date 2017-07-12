<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCaseAdviceTable extends Migration
{
    public function up()
    {
        Schema::create('case_advice', function(Blueprint $table) {
            $table->increments('id');
            $table->string('name', 255);
            $table->string('name_en', 255)->nullable();
            $table->string('description', 1000);
            $table->string('description_en', 1000)->nullable();
            $table->unsignedTinyInteger('type')->default(0);
            $table->unsignedTinyInteger('status')->default(0);
            $table->string('phone', 20)->nullable();
            $table->string('adviser', 255)->nullable();
            $table->string('slug', 255);
            $table->string('slug_en', 255)->nullable();
            $table->unsignedInteger('step_count')->default(0);
            $table->unsignedInteger('order')->default(1);
            $table->unsignedInteger('view_count')->default(0);
        });
    }

    public function down()
    {
        Schema::dropIfExists('case_advice');
    }
}
