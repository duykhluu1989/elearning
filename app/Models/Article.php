<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    const TYPE_EXPERT_ARTICLE_DB = 0;
    const TYPE_STATIC_ARTICLE_DB = 1;

    protected $table = 'article';

    public $timestamps = false;

    public function user()
    {
        return $this->belongsTo('App\Models\User', 'user_id');
    }
}