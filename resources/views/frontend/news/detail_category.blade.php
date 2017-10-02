@extends('frontend.layouts.main')

@section('og_description', \App\Libraries\Helpers\Utility::getValueByLocale($category, 'name'))

@section('og_image', $category->image)

@section('page_heading', \App\Libraries\Helpers\Utility::getValueByLocale($category, 'name'))

@section('section')

    @include('frontend.layouts.partials.header')

    @include('frontend.layouts.partials.headtext')

    @include('frontend.layouts.partials.breabcrumb', ['breabcrumbTitle' => \App\Libraries\Helpers\Utility::getValueByLocale($category, 'name')])

    <main>
        <section class="khoahoc bg_gray">
            <div class="container">
                <div class="row">
                    <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
                        <div class="navleft">
                            <h5>@lang('theme.news_category_list')</h5>
                            <ul class="list_navLeft">

                                @foreach($listCategories as $listCategory)
                                    <li<?php echo ($listCategory->id == $category->id ? ' class="active"' : ''); ?>><a href="{{ action('Frontend\NewsController@detailCategory', ['id' => $listCategory->id, 'slug' => \App\Libraries\Helpers\Utility::getValueByLocale($listCategory, 'slug')]) }}">{{ \App\Libraries\Helpers\Utility::getValueByLocale($listCategory, 'name') }}</a></li>
                                @endforeach

                            </ul>
                        </div>
                    </div>
                    <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                        <div class="main_content">

                            @foreach($articles as $article)
                                <div class="row item_news">
                                    <div class="col-lg-3">
                                        <a href="{{ action('Frontend\NewsController@detailArticle', ['id' => $article->id, 'slug' => \App\Libraries\Helpers\Utility::getValueByLocale($article, 'slug')]) }}"><img src="{{ $article->image }}" alt="{{ \App\Libraries\Helpers\Utility::getValueByLocale($article, 'name') }}" class="img-responsive w100p"></a>
                                    </div>
                                    <div class="col-lg-9">
                                        <h4><a href="{{ action('Frontend\NewsController@detailArticle', ['id' => $article->id, 'slug' => \App\Libraries\Helpers\Utility::getValueByLocale($article, 'slug')]) }}">{{ \App\Libraries\Helpers\Utility::getValueByLocale($article, 'name') }}</a></h4>
                                        <p class="date"><small>{{ $article->published_at }}</small></p>
                                        <p>{{ \App\Libraries\Helpers\Utility::getValueByLocale($article, 'short_description') }}</p>
                                    </div>
                                </div>
                            @endforeach

                            <div class="row">
                                <div class="col-lg-12 text-center">
                                    <ul class="pagination">
                                        @if($articles->lastPage() > 1)
                                            @if($articles->currentPage() > 1)
                                                <li><a href="{{ $articles->previousPageUrl() }}">&laquo;</a></li>
                                                <li><a href="{{ $articles->url(1) }}">1</a></li>
                                            @endif

                                            @for($i = 2;$i >= 1;$i --)
                                                @if($articles->currentPage() - $i > 1)
                                                    @if($articles->currentPage() - $i > 2 && $i == 2)
                                                        <li>...</li>
                                                        <li><a href="{{ $articles->url($articles->currentPage() - $i) }}">{{ $articles->currentPage() - $i }}</a></li>
                                                    @else
                                                        <li><a href="{{ $articles->url($articles->currentPage() - $i) }}">{{ $articles->currentPage() - $i }}</a></li>
                                                    @endif
                                                @endif
                                            @endfor

                                            <li class="active"><a href="javascript:void(0)">{{ $articles->currentPage() }}</a></li>

                                            @for($i = 1;$i <= 2;$i ++)
                                                @if($articles->currentPage() + $i < $articles->lastPage())
                                                    @if($articles->currentPage() + $i < $articles->lastPage() - 1 && $i == 2)
                                                        <li><a href="{{ $articles->url($articles->currentPage() + $i) }}">{{ $articles->currentPage() + $i }}</a></li>
                                                        <li>...</li>
                                                    @else
                                                        <li><a href="{{ $articles->url($articles->currentPage() + $i) }}">{{ $articles->currentPage() + $i }}</a></li>
                                                    @endif
                                                @endif
                                            @endfor

                                            @if($articles->currentPage() < $articles->lastPage())
                                                <li><a href="{{ $articles->url($articles->lastPage()) }}">{{ $articles->lastPage() }}</a></li>
                                                <li><a href="{{ $articles->nextPageUrl() }}">&raquo;</a></li>
                                            @endif
                                        @endif
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                @include('frontend.news.partials.news_rss', ['newsCategories' => $listCategories])

            </div>
        </section>
    </main>

@stop