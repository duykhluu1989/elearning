<?php

namespace App\Http\Controllers\Frontend;

use Illuminate\Support\Facades\Cookie;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Libraries\Helpers\Utility;
use App\Models\Course;
use App\Models\Article;

class PageController extends Controller
{
    public function detailPage(Request $request, $id, $slug)
    {
        $page = Article::select('id', 'name', 'name_en', 'content', 'content_en', 'short_description', 'short_description_en', 'image', 'group')
            ->where('type', Article::TYPE_STATIC_ARTICLE_DB)
            ->where('status', Course::STATUS_PUBLISH_DB)
            ->where('id', $id)
            ->where(function($query) use($slug) {
                $query->where('slug', $slug)->orWhere('slug_en', $slug);
            })->first();

        if(empty($page))
            return view('frontend.errors.404');

        if($request->hasCookie(Utility::VIEW_ARTICLE_COOKIE_NAME))
        {
            $viewIds = $request->cookie(Utility::VIEW_ARTICLE_COOKIE_NAME);
            $viewIds = explode(';', $viewIds);

            if(!in_array($page->id, $viewIds))
            {
                $page->increment('view_count', 1);

                $viewIds[] = $page->id;
                $viewIds = implode(';', $viewIds);

                Cookie::queue(Utility::VIEW_ARTICLE_COOKIE_NAME, $viewIds, Utility::MINUTE_ONE_DAY);
            }
        }
        else
        {
            $page->increment('view_count', 1);

            Cookie::queue(Utility::VIEW_ARTICLE_COOKIE_NAME, $page->id, Utility::MINUTE_ONE_DAY);
        }

        if($page->group !== null)
        {
            switch($page->group)
            {
                case Article::STATIC_ARTICLE_GROUP_INTRO_DB:

                    return $this->detailIntroPage($page);

                    break;

                case Article::STATIC_ARTICLE_GROUP_GUIDE_DB:

                    return $this->detailGuidePage($page);

                    break;

                case Article::STATIC_ARTICLE_GROUP_COLLABORATOR_DB:

                    return $this->detailCollaboratorPage($page);

                    break;

                case Article::STATIC_ARTICLE_GROUP_FAQ_DB:

                    return $this->detailFaqPage($page);

                    break;
            }
        }

        return $this->page($page);
    }

    public function page($page)
    {
        return view('frontend.pages.detail_page', [
            'page' => $page,
        ]);
    }

    public function detailIntroPage($page)
    {
        $sameGroupPages = Article::select('id', 'name', 'name_en', 'slug', 'slug_en')
            ->where('type', Article::TYPE_STATIC_ARTICLE_DB)
            ->where('status', Course::STATUS_PUBLISH_DB)
            ->where('group', Article::STATIC_ARTICLE_GROUP_INTRO_DB)
            ->orderBy('order', 'desc')
            ->get();

        return view('frontend.pages.detail_intro_page', [
            'page' => $page,
            'sameGroupPages' => $sameGroupPages,
        ]);
    }

    public function detailGuidePage($page)
    {
        $sameGroupPages = Article::select('id', 'name', 'name_en', 'slug', 'slug_en')
            ->where('type', Article::TYPE_STATIC_ARTICLE_DB)
            ->where('status', Course::STATUS_PUBLISH_DB)
            ->where('group', Article::STATIC_ARTICLE_GROUP_GUIDE_DB)
            ->orderBy('order', 'desc')
            ->get();

        return view('frontend.pages.detail_guide_page', [
            'page' => $page,
            'sameGroupPages' => $sameGroupPages,
        ]);
    }

    public function detailCollaboratorPage($page)
    {
        $sameGroupPages = Article::select('id', 'name', 'name_en', 'slug', 'slug_en')
            ->where('type', Article::TYPE_STATIC_ARTICLE_DB)
            ->where('status', Course::STATUS_PUBLISH_DB)
            ->where('group', Article::STATIC_ARTICLE_GROUP_COLLABORATOR_DB)
            ->orderBy('order', 'desc')
            ->get();

        return view('frontend.pages.detail_collaborator_page', [
            'page' => $page,
            'sameGroupPages' => $sameGroupPages,
        ]);
    }

    public function detailFaqPage($page)
    {
        return view('frontend.pages.detail_faq_page', [
            'page' => $page,
        ]);
    }
}