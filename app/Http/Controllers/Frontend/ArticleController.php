<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Libraries\Helpers\Utility;
use App\Models\Course;
use App\Models\Article;
use App\Models\User;

class ArticleController extends Controller
{
    public function sample()
    {
        return view('frontend.articles.sample');
    }

    public function tintuc()
    {
        return view('frontend.articles.tintuc');
    }

    public function adminExpert()
    {
        $experts = User::where('expert', Utility::ACTIVE_DB)->orderBy('id', 'desc')->get();

        $articles = Article::where('type', Article::TYPE_EXPERT_ARTICLE_DB)
            ->where('status', Course::STATUS_PUBLISH_DB)
            ->paginate(Utility::FRONTEND_ROWS_PER_PAGE);

        return view('frontend.articles.admin_expert', [
            'experts' => $experts,
            'articles' => $articles,
        ]);
    }
}