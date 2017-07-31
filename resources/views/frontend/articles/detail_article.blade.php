@extends('frontend.layouts.main')

@section('og_description', \App\Libraries\Helpers\Utility::getValueByLocale($article, 'short_description'))

@section('og_image', $article->image)

@section('page_heading', \App\Libraries\Helpers\Utility::getValueByLocale($article, 'name'))

@section('section')

    @include('frontend.layouts.partials.header')

    @include('frontend.layouts.partials.headtext')

    @include('frontend.articles.partials.article_breadcrumb')

    <main>
        <section class="khoahoc bg_gray">
            <div class="container">
                <div class="row">
                    <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
                        <div class="navleft">
                            <h5>@lang('theme.all_expert')</h5>
                            <hr>
                            <ul class="list_navLeft">

                                @foreach($experts as $expert)
                                    <li class="{{ $expert->id == $article->user_id ? 'active' : '' }}">
                                        <div class="row">
                                            <div class="col-xs-4">
                                                @if(!empty($expert->avatar))
                                                    <a href="{{ action('Frontend\ArticleController@detailExpert', ['id' => $expert->id]) }}"><img src="{{ $expert->avatar }}" alt="User Avatar" class="img-responsive"></a>
                                                @endif
                                            </div>
                                            <div class="col-xs-8">
                                                <a href="{{ action('Frontend\ArticleController@detailExpert', ['id' => $expert->id]) }}">{{ $expert->profile->name }}</a>
                                                <p><small>{{ $expert->profile->title }}</small></p>
                                            </div>
                                        </div>
                                    </li>
                                @endforeach

                            </ul>
                        </div>
                    </div>
                    <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                        <div class="main_content">
                            <h2>{{ \App\Libraries\Helpers\Utility::getValueByLocale($article, 'name') }}</h2>

                            @if(!empty($article->image))
                                <img src="{{ $article->image }}" alt="{{ \App\Libraries\Helpers\Utility::getValueByLocale($article, 'name') }}" class="img-responsive w100p mb15">
                            @endif

                            <?php
                            echo \App\Libraries\Helpers\Utility::getValueByLocale($article, 'content');
                            ?>

                            <h2>@lang('theme.relative_article')</h2>
                            <ul class="list_tintuclienquan">

                                @foreach($relatedArticles as $relatedArticle)
                                    <li><a href="{{ action('Frontend\ArticleController@detailArticle', ['id' => $relatedArticle->id, 'slug' => \App\Libraries\Helpers\Utility::getValueByLocale($relatedArticle, 'slug')]) }}"><i class="fa fa-check-square-o" aria-hidden="true"></i>{{ \App\Libraries\Helpers\Utility::getValueByLocale($relatedArticle, 'name') }}</a></li>
                                @endforeach

                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>

@stop
