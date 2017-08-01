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
                                    <h4><a href="chitiettintuc.php">Lịch sự kiện và tin vắn chứng khoán ngày 12/6</a></h4>
                                    <div class="row">
                                        <div class="col-xs-6">
                                            <a href="chitiettintuc.php"><img src="images/img_giohang.png" alt="" class="img-responsive"></a>
                                        </div>
                                        <div class="col-xs-6">
                                            <p>Tổng hợp toàn bộ tin vắn nổi bật liên quan đến doanh nghiệp niêm yết trên hai sàn chứng khoán.</p>
                                        </div>
                                    </div>
                                    <hr>
                                    <ul class="list_tintuc">

                                        @if(isset($newsRss[$newsCategory->id]))
                                            @foreach($newsRss[$newsCategory->id] as $item)
                                                <li><a href="{{ $item['link'] }}">{{ $item['title'] }} <span class="gray">({{ $item['pubDate'] }})</span></a></li>
                                            @endforeach
                                        @endif

                                    </ul>
                                </div>
                            </div>
                        </div>
                    @endforeach

                </div>
            </div>
        </section>
    </main>

@stop
