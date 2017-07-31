<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\NewsCategory;

class NewsController extends Controller
{
    public function adminCategory()
    {
        return view('frontend.news.admin_category');
    }
}