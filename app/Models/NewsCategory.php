<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Libraries\Helpers\Utility;

class NewsCategory extends Model
{
    protected $table = 'news_category';

    public $timestamps = false;

    protected static function boot()
    {
        parent::boot();

        self::saving(function(NewsCategory $category) {

            if($category->getOriginal('status') != $category->status || $category->getOriginal('parent_status') != $category->parent_status)
            {
                $page = 1;

                do
                {
                    $articles = NewsArticle::select('id', 'category_status')
                        ->where('category_id', $category->id)
                        ->paginate(Utility::LARGE_SET_LIMIT, ['*'], 'page', $page);

                    foreach($articles as $article)
                    {
                        $article->category_status = $category->status;
                        $article->save();
                    }

                    $page ++;

                    $countArticles = count($articles);
                }
                while($countArticles > 0);
            }

        });
    }
}