<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCertificateTable extends Migration
{
    public function up()
    {
        Schema::create('certificate', function(Blueprint $table) {
            $table->increments('id');
            $table->string('name', 255);
            $table->string('name_en', 255)->nullable();
            $table->unsignedTinyInteger('status')->default(0);
            $table->double('price')->unsigned()->nullable();
            $table->unsignedInteger('order')->default(1);
            $table->string('code', 20);
        });
    }

    public function down()
    {
        Schema::dropIfExists('certificate');
    }
}
