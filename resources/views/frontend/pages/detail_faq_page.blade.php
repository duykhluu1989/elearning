@extends('frontend.layouts.main')

@section('og_description', \App\Libraries\Helpers\Utility::getValueByLocale($page, 'short_description'))

@section('og_image', $page->image)

@section('page_heading', \App\Libraries\Helpers\Utility::getValueByLocale($page, 'name'))

@section('section')

    @include('frontend.layouts.partials.header')

    @include('frontend.layouts.partials.breabcrumb', ['breabcrumbTitle' => \App\Libraries\Helpers\Utility::getValueByLocale($page, 'name')])

    <main>
        <section class="bg_gray">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="main_content mb60">
                            <h2>{{ \App\Libraries\Helpers\Utility::getValueByLocale($page, 'name') }}</h2>

                            @if(!empty($page->image))
                                <img src="{{ $page->image }}" alt="{{ \App\Libraries\Helpers\Utility::getValueByLocale($page, 'name') }}" class="img-responsive w100p mb15">
                            @endif

                            <div class="panel-group" id="accordion">

                                <?php
                                $details = array();

                                $content = \App\Libraries\Helpers\Utility::getValueByLocale($page, 'content');
                                if(!empty($content))
                                    $details = json_decode($content, true);

                                $i = 1;
                                ?>

                                @foreach($details as $key => $detail)
                                    <div class="panel panel-default">
                                        <div class="panel-heading">
                                            <h4 class="panel-title">
                                                <a data-toggle="collapse" data-parent="#accordion" href="#PageDetail_{{ $key }}">{{ isset($detail['title']) ? $detail['title'] : '' }}</a>
                                            </h4>
                                        </div>
                                        <div id="PageDetail_{{ $key }}" class="panel-collapse collapse{{ $i == 1 ? ' in' : '' }}">
                                            <div class="panel-body">
                                                <p>{{ isset($detail['description']) ? $detail['description'] : '' }}</p>
                                            </div>
                                        </div>
                                    </div>
                                    <?php
                                    $i ++;
                                    ?>
                                @endforeach

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>

@stop
