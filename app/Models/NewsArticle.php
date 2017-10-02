<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NewsArticle extends Model
{
    protected $table = 'news_article';

    public $timestamps = false;

    public function newsCategory()
    {
        return $this->belongsTo('App\Models\NewsCategory', 'category_id');
    }
}