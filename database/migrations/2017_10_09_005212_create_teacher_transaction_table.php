<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTeacherTransactionTable extends Migration
{
    public function up()
    {
        Schema::create('teacher_transaction', function(Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('teacher_id');
            $table->unsignedInteger('order_id')->nullable();
            $table->unsignedTinyInteger('type')->default(0);
            $table->double('commission_percent')->unsigned()->nullable();
            $table->double('commission_amount')->unsigned()->nullable();
            $table->dateTime('created_at');
            $table->string('note', 255)->nullable();
        });
    }

    public function down()
    {
        Schema::dropIfExists('teacher_transaction');
    }
}
