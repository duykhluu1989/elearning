<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCollaboratorTable extends Migration
{
    public function up()
    {
        Schema::create('collaborator', function(Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('user_id');
            $table->string('code', 40);
            $table->unsignedInteger('rank_id');
            $table->double('current_revenue')->unsigned()->default(0);
            $table->double('current_commission')->unsigned()->default(0);
            $table->double('total_revenue')->unsigned()->default(0);
            $table->double('total_commission')->unsigned()->default(0);
            $table->dateTime('upranked_at')->nullable();
            $table->double('create_discount_percent')->unsigned()->default(1);
            $table->double('commission_percent')->unsigned()->default(1);
            $table->unsignedInteger('parent_id')->nullable();
            $table->unsignedTinyInteger('status')->default(1);
            $table->string('bank', 255)->nullable();
            $table->string('bank_holder', 255)->nullable();
            $table->string('bank_number', 20)->nullable();
        });
    }

    public function down()
    {
        Schema::dropIfExists('collaborator');
    }
}
