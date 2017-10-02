@extends('frontend.layouts.main')

@section('og_description', \App\Libraries\Helpers\Utility::getValueByLocale($article, 'short_description'))

@section('og_image', $article->image)

@section('page_heading', \App\Libraries\Helpers\Utility::getValueByLocale($article, 'name'))

@section('section')

    @include('frontend.layouts.partials.header')

    @include('frontend.layouts.partials.headtext')

    @include('frontend.news.partials.article_breadcrumb')

    <main>
        <section class="khoahoc bg_gray">
            <div class="container">
                <div class="row">
                    <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
                        <div class="navleft">
                            <h5>@lang('theme.news_category_list')</h5>
                            <ul class="list_navLeft">

                                @foreach($listCategories as $listCategory)
                                    <li<?php echo ($listCategory->id == $article->category_id ? ' class="active"' : ''); ?>><a href="{{ action('Frontend\NewsController@detailCategory', ['id' => $listCategory->id, 'slug' => \App\Libraries\Helpers\Utility::getValueByLocale($listCategory, 'slug')]) }}">{{ \App\Libraries\Helpers\Utility::getValueByLocale($listCategory, 'name') }}</a></li>
                                @endforeach

                            </ul>
                        </div>
                    </div>
                    <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                        <div class="main_content">
                            <h2>{{ \App\Libraries\Helpers\Utility::getValueByLocale($article, 'name') }}</h2>

                            @if(!empty($article->image))
                                <img src="{{ $article->image }}" alt="{{ \App\Libraries\Helpers\Utility::getValueByLocale($article, 'name') }}" class="img-responsive w100p mb30">
                            @endif

                            <?php
                            echo \App\Libraries\Helpers\Utility::getValueByLocale($article, 'content');
                            ?>
                        </div>
                    </div>
                </div>

                @include('frontend.news.partials.news_rss', ['newsCategories' => $listCategories])

            </div>
        </section>
    </main>

@stop