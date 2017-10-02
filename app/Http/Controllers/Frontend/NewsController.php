<?php

namespace App\Http\Controllers\Frontend;

use Illuminate\Support\Facades\Redis;
use App\Http\Controllers\Controller;
use App\Libraries\Helpers\Utility;
use App\Models\Course;
use App\Models\NewsCategory;
use App\Models\NewsArticle;

class NewsController extends Controller
{
    const REDIS_KEY = 'news_rss';

    protected function getNewsRss($newsCategories)
    {
        $newsRss = Redis::command('get', [self::REDIS_KEY]);

        if(empty($newsRss))
        {
            $newsRss = array();
            foreach($newsCategories as $newsCategory)
            {
                $listRss = array();
                if(!empty($newsCategory->rss))
                    $listRss = json_decode($newsCategory->rss, true);

                foreach($listRss as $rss)
                {
                    if(isset($rss['rss']))
                    {
                        $xmlTextNews = file_get_contents($rss['rss']);

                        $xmlNews = new \SimpleXMLElement($xmlTextNews);

                        if(isset($xmlNews->channel->item))
                        {
                            foreach($xmlNews->channel->item as $item)
                            {
                                $newsRss[$newsCategory->id][] = [
                                    'title' => (string)$item->title,
                                    'link' => (string)$item->link,
                                    'description' => (string)$item->description,
                                    'pubDate' => strtotime((string)$item->pubDate),
                                ];
                            }
                        }
                    }
                }
            }

            Redis::command('setex', [self::REDIS_KEY, Utility::SECOND_ONE_HOUR, json_encode($newsRss)]);
        }
        else
            $newsRss = json_decode($newsRss, true);

        return $newsRss;
    }

    public function detailCategory($id, $slug)
    {
        $category = NewsCategory::select('id', 'name', 'name_en', 'slug', 'slug_en', 'image')
            ->where('id', $id)
            ->where('status', Utility::ACTIVE_DB)
            ->where(function($query) use($slug) {
                $query->where('slug', $slug)->orWhere('slug_en', $slug);
            })->first();

        if(empty($category))
            return view('frontend.errors.404');

        $listCategories = NewsCategory::select('id', 'name', 'name_en', 'slug', 'slug_en', 'rss')
            ->where('status', Utility::ACTIVE_DB)
            ->orderBy('order', 'desc')
            ->get();

        $articles = NewsArticle::select('id', 'name', 'name_en', 'short_description', 'short_description_en', 'image', 'slug', 'slug_en', 'published_at')
            ->where('category_id', $category->id)
            ->where('status', Course::STATUS_PUBLISH_DB)
            ->where('category_status', Utility::ACTIVE_DB)
            ->orderBy('published_at', 'desc')
            ->paginate(Utility::FRONTEND_ROWS_PER_PAGE);

        return view('frontend.news.detail_category', [
            'category' => $category,
            'listCategories' => $listCategories,
            'articles' => $articles,
            'newsRss' => $this->getNewsRss($listCategories),
        ]);
    }

    public function detailArticle($id, $slug)
    {
        $article = NewsArticle::with(['newsCategory' => function($query) {
            $query->select('id', 'name', 'name_en', 'slug', 'slug_en');
        }])->select('id', 'name', 'name_en', 'short_description', 'short_description_en', 'image', 'slug', 'slug_en', 'published_at', 'category_id')
            ->where('id', $id)
            ->where('status', Course::STATUS_PUBLISH_DB)
            ->where('category_status', Utility::ACTIVE_DB)
            ->where(function($query) use($slug) {
                $query->where('slug', $slug)->orWhere('slug_en', $slug);
            })->first();

        if(empty($article))
            return view('frontend.errors.404');

        $listCategories = NewsCategory::select('id', 'name', 'name_en', 'slug', 'slug_en', 'rss')
            ->where('status', Utility::ACTIVE_DB)
            ->orderBy('order', 'desc')
            ->get();

        return view('frontend.news.detail_article', [
            'article' => $article,
            'listCategories' => $listCategories,
            'newsRss' => $this->getNewsRss($listCategories),
        ]);
    }

    public static function getNewNews()
    {
        if(request()->hasCookie(Utility::VISIT_START_TIME_COOKIE_NAME))
        {
            $newsRss = Redis::command('get', [self::REDIS_KEY]);

            if(!empty($newsRss))
            {
                $newsRss = json_decode($newsRss, true);

                $newNews = array();

                foreach($newsRss as $category)
                {
                    foreach($category as $item)
                    {
                        if($item['pubDate'] > request()->cookie(Utility::VISIT_START_TIME_COOKIE_NAME))
                            $newNews[] = $item;
                    }
                }

                if(count($newNews) > 0)
                    return $newNews;
            }
        }

        return array();
    }
}