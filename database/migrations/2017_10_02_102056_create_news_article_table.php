<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNewsArticleTable extends Migration
{
    public function up()
    {
        Schema::create('news_article', function(Blueprint $table) {
            $table->increments('id');
            $table->string('name', 255);
            $table->string('name_en', 255)->nullable();
            $table->string('short_description', 1000)->nullable();
            $table->string('short_description_en', 1000)->nullable();
            $table->text('content');
            $table->text('content_en')->nullable();
            $table->unsignedTinyInteger('status')->default(0);
            $table->dateTime('created_at');
            $table->dateTime('published_at')->nullable();
            $table->string('image', 1000)->nullable();
            $table->unsignedInteger('view_count')->default(0);
            $table->string('slug', 255);
            $table->string('slug_en', 255)->nullable();
            $table->unsignedInteger('order')->default(1);
            $table->unsignedInteger('category_id');
            $table->unsignedTinyInteger('category_status')->default(0);
        });
    }

    public function down()
    {
        Schema::dropIfExists('news_article');
    }
}
