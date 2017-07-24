<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCollaboratorTransactionTable extends Migration
{
    public function up()
    {
        Schema::create('collaborator_transaction', function(Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('collaborator_id');
            $table->unsignedInteger('order_id')->nullable();
            $table->unsignedTinyInteger('type')->default(0);
            $table->double('commission_percent')->unsigned()->nullable();
            $table->double('commission_amount')->unsigned()->nullable();
            $table->dateTime('created_at');
        });
    }

    public function down()
    {
        Schema::dropIfExists('collaborator_transaction');
    }
}
