<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Article;

class PageController extends Controller
{
    public function detailPage($id, $slug)
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