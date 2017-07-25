<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;

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
}