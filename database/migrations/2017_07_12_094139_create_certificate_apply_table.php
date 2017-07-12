<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCertificateApplyTable extends Migration
{
    public function up()
    {
        Schema::create('certificate_apply', function(Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('certificate_id');
            $table->string('name', 255);
            $table->string('phone', 20);
            $table->unsignedInteger('status')->default(0);
            $table->dateTime('created_at');
        });
    }

    public function down()
    {
        Schema::dropIfExists('certificate_apply');
    }
}
