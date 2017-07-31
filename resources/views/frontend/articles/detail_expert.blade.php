@extends('frontend.layouts.main')

@section('og_description', trans('theme.expert') . '-' . $expert->profile->name)

@section('page_heading', trans('theme.expert') . '-' . $expert->profile->name)

@section('section')

    @include('frontend.layouts.partials.header')

    @include('frontend.layouts.partials.headtext')

    @include('frontend.articles.partials.expert_breadcrumb')

    <main>
        <section class="khoahoc bg_gray">
            <div class="container">
                <div class="row">
                    <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
                        <div class="navleft">
                            <h5>@lang('theme.all_expert')</h5>
                            <hr>
                            <ul class="list_navLeft">

                                @foreach($listExperts as $listExpert)
                                    <li class="{{ $listExpert->id == $expert->id ? 'active' : '' }}">
                                        <div class="row">
                                            <div class="col-xs-4">
                                                @if(!empty($listExpert->avatar))
                                                    <a href="{{ action('Frontend\ArticleController@detailExpert', ['id' => $listExpert->id]) }}"><img src="{{ $listExpert->avatar }}" alt="User Avatar" class="img-responsive"></a>
                                                @endif
                                            </div>
                                            <div class="col-xs-8">
                                                <a href="{{ action('Frontend\ArticleController@detailExpert', ['id' => $listExpert->id]) }}">{{ $listExpert->profile->name }}</a>
                                                <p><small>{{ $listExpert->profile->title }}</small></p>
                                            </div>
                                        </div>
                                    </li>
                                @endforeach

                            </ul>
                        </div>
                    </div>
                    <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                        <div class="main_content">
                            <div class="row item_news item_news_top">
                                <div class="col-lg-4">
                                    @if(!empty($expert->avatar))
                                        <a href="javascript:void(0)"><img src="{{ $expert->avatar }}" alt="User Avatar" class="img-responsive w100p"></a>
                                    @endif
                                </div>
                                <div class="col-lg-8">
                                    <h4><a href="javascript:void(0)">{{ $expert->profile->name }}</a></h4>
                                    <p>{{ $expert->profile->title }}</p>
                                </div>
                            </div>

                            @foreach($articles as $article)
                                <div class="row item_news">
                                    <div class="col-lg-12">
                                        <h4><a href="{{ action('Frontend\ArticleController@detailArticle', ['id' => $article->id, 'slug' => \App\Libraries\Helpers\Utility::getValueByLocale($article, 'slug')]) }}">{{ \App\Libraries\Helpers\Utility::getValueByLocale($article, 'name') }}</a></h4>
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
            </div>
        </section>
    </main>

@stop
