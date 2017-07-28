<?php

namespace App\Http\Controllers\Frontend;

use Illuminate\Support\Facades\Cookie;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Libraries\Helpers\Utility;
use App\Models\Course;
use App\Models\Article;
use App\Models\User;

class ArticleController extends Controller
{
    public function tintuc()
    {
        return view('frontend.articles.tintuc');
    }

    public function adminExpert()
    {
        $experts = User::with(['profile' => function($query) {
            $query->select('user_id', 'name', 'title');
        }])->select('id', 'avatar')
            ->where('expert', Utility::ACTIVE_DB)
            ->orderBy('id', 'desc')
            ->get();

        $articles = Article::select('id', 'name', 'name_en', 'short_description', 'short_description_en', 'slug', 'slug_en', 'image')
            ->where('type', Article::TYPE_EXPERT_ARTICLE_DB)
            ->where('status', Course::STATUS_PUBLISH_DB)
            ->orderBy('published_at', 'desc')
            ->paginate(Utility::FRONTEND_ROWS_PER_PAGE);

        return view('frontend.articles.admin_expert', [
            'experts' => $experts,
            'articles' => $articles,
        ]);
    }

    public function detailExpert($id)
    {
        $expert = User::with(['profile' => function($query) {
            $query->select('user_id', 'name', 'title');
        }])->select('id', 'avatar')
            ->where('expert', Utility::ACTIVE_DB)
            ->find($id);

        if(empty($expert))
            return view('frontend.errors.404');

        $listExperts = User::with(['profile' => function($query) {
            $query->select('user_id', 'name', 'title');
        }])->select('id', 'avatar')
            ->where('expert', Utility::ACTIVE_DB)
            ->orderBy('id', 'desc')
            ->get();

        $articles = Article::select('id', 'name', 'name_en', 'short_description', 'short_description_en', 'slug', 'slug_en', 'image', 'published_at')
            ->where('type', Article::TYPE_EXPERT_ARTICLE_DB)
            ->where('status', Course::STATUS_PUBLISH_DB)
            ->where('user_id', $expert->id)
            ->orderBy('published_at', 'desc')
            ->paginate(Utility::FRONTEND_ROWS_PER_PAGE);

        return view('frontend.articles.detail_expert', [
            'expert' => $expert,
            'listExperts' => $listExperts,
            'articles' => $articles,
        ]);
    }

    public function detailArticle(Request $request, $id, $slug)
    {
        $article = Article::with(['user' => function($query){
            $query->select('id');
        }, 'user.profile' => function($query) {
            $query->select('user_id', 'name');
        }])->select('id', 'user_id', 'name', 'name_en', 'short_description', 'short_description_en', 'image', 'content', 'content_en', 'published_at')
            ->where('id', $id)
            ->where('type', Article::TYPE_EXPERT_ARTICLE_DB)
            ->where('status', Course::STATUS_PUBLISH_DB)
            ->where(function($query) use($slug) {
                $query->where('slug', $slug)->orWhere('slug_en', $slug);
            })->first();

        if(empty($article))
            return view('frontend.errors.404');

        if($request->hasCookie(Utility::VIEW_ARTICLE_COOKIE_NAME))
        {
            $viewIds = $request->cookie(Utility::VIEW_ARTICLE_COOKIE_NAME);
            $viewIds = explode(';', $viewIds);

            if(!in_array($article->id, $viewIds))
            {
                $article->increment('view_count', 1);

                $viewIds[] = $article->id;
                $viewIds = implode(';', $viewIds);

                Cookie::queue(Utility::VIEW_ARTICLE_COOKIE_NAME, $viewIds, Utility::MINUTE_ONE_DAY);
            }
        }
        else
        {
            $article->increment('view_count', 1);

            Cookie::queue(Utility::VIEW_ARTICLE_COOKIE_NAME, $article->id, Utility::MINUTE_ONE_DAY);
        }

        $experts = User::with(['profile' => function($query) {
            $query->select('user_id', 'name', 'title');
        }])->select('id', 'avatar')
            ->where('expert', Utility::ACTIVE_DB)
            ->orderBy('id', 'desc')
            ->get();

        $relatedArticles = Article::select('id', 'name', 'name_en', 'slug', 'slug_en')
            ->where('type', Article::TYPE_EXPERT_ARTICLE_DB)
            ->where('status', Course::STATUS_PUBLISH_DB)
            ->where('published_at', '<=', $article->published_at)
            ->where('id', '<>', $article->id)
            ->limit(Utility::FRONTEND_HOME_ITEM_LIMIT)
            ->orderBy('published_at', 'desc')
            ->get();

        return view('frontend.articles.detail_article', [
            'article' => $article,
            'experts' => $experts,
            'relatedArticles' => $relatedArticles,
        ]);
    }
}