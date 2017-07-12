<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCaseAdviceStepTable extends Migration
{
    public function up()
    {
        Schema::create('case_advice_step', function(Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('case_id');
            $table->string('content', 1000)->nullable();
            $table->string('content_en', 1000)->nullable();
            $table->unsignedInteger('step')->default(1);
            $table->unsignedTinyInteger('type')->default(0);
        });
    }

    public function down()
    {
        Schema::dropIfExists('case_advice_step');
    }
}
