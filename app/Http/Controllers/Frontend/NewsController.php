<?php

namespace App\Http\Controllers\Frontend;

use Illuminate\Support\Facades\Redis;
use App\Http\Controllers\Controller;
use App\Libraries\Helpers\Utility;
use App\Models\NewsCategory;

class NewsController extends Controller
{
    const REDIS_KEY = 'news_rss';

    public function adminCategory()
    {
        $newsCategories = NewsCategory::select('id', 'name', 'name_en', 'slug', 'slug_en', 'rss')
            ->where('status', Utility::ACTIVE_DB)
            ->orderBy('order', 'desc')
            ->get();

        $newsRss = Redis::command('get', [self::REDIS_KEY]);

        if(empty($newsRss))
        {
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

        return view('frontend.news.admin_category', [
            'newsCategories' => $newsCategories,
            'newsRss' => $newsRss,
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