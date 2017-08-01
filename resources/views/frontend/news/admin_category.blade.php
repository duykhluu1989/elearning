@extends('frontend.layouts.main')

@section('page_heading', trans('theme.news_title'))

@section('section')

    @include('frontend.layouts.partials.header')

    @include('frontend.layouts.partials.headtext')

    @include('frontend.layouts.partials.breabcrumb', ['breabcrumbTitle' => trans('theme.news_title')])

    <main>
        <section class="bg_gray">
            <div class="container">
                <div class="row">

                    @foreach($newsCategories as $newsCategory)
                        <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
                            <div class="panel panel-default panel_tintuc boxmH">
                                <div class="panel-heading">
                                    <h1 class="panel-title"><a href="javascript:void(0)">{{ \App\Libraries\Helpers\Utility::getValueByLocale($newsCategory, 'name') }}</a></h1>
                                </div>
                                <div class="panel-body">
                                    @if(isset($newsRss[$newsCategory->id]))
                                        <?php
                                        $item = array_shift($newsRss[$newsCategory->id]);
                                        ?>
                                        <h4><a href="{{ $item['link'] }}">{{ $item['title'] }}</a></h4>
                                        <div class="row">
                                            <?php
                                            echo $item['description'];
                                            ?>
                                        </div>
                                        <hr />
                                        <ul class="list_tintuc">
                                            @foreach($newsRss[$newsCategory->id] as $item)
                                                <li><a href="{{ $item['link'] }}">{{ $item['title'] }} <span class="gray">({{ date('Y-m-d H:i:s', $item['pubDate']) }})</span></a></li>
                                            @endforeach
                                        </ul>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach

                </div>
            </div>
        </section>
    </main>

@stop
