@extends('frontend.layouts.main')

@section('page_heading', trans('theme.expert'))

@section('section')

    @include('frontend.layouts.partials.header')

    @include('frontend.layouts.partials.headtext')

    @include('frontend.layouts.partials.breabcrumb', ['breabcrumbTitle' => trans('theme.expert')])

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
                                    <li>
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

                            @foreach($articles as $article)
                                <div class="row item_news">
                                    <div class="col-lg-3">
                                        <a href="{{ action('Frontend\ArticleController@detailArticle', ['id' => $article->id, 'slug' => \App\Libraries\Helpers\Utility::getValueByLocale($article, 'slug')]) }}"><img src="{{ $article->image }}" alt="{{ \App\Libraries\Helpers\Utility::getValueByLocale($article, 'name') }}" class="img-responsive w100p"></a>
                                    </div>
                                    <div class="col-lg-9">
                                        <h4><a href="{{ action('Frontend\ArticleController@detailArticle', ['id' => $article->id, 'slug' => \App\Libraries\Helpers\Utility::getValueByLocale($article, 'slug')]) }}">{{ \App\Libraries\Helpers\Utility::getValueByLocale($article, 'name') }}</a></h4>
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
