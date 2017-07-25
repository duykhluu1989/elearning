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
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="row component-wrapper">
                                        <div class="col-lg-3" style="padding:0">
                                            <ul class="nav nav-tabs tabs-left">

                                                <?php
                                                $i = 1;
                                                ?>
                                                @foreach($sameGroupPages as $sameGroupPage)
                                                    @if($sameGroupPage->id == $page->id)
                                                        <li class="active"><a href="javascript:void(0)" class="disabled">{{ $i . '. ' . \App\Libraries\Helpers\Utility::getValueByLocale($sameGroupPage, 'name') }}</a></li>
                                                    @else
                                                        <li><a href="{{ action('Frontend\PageController@detailPage', ['id' => $sameGroupPage->id, 'slug' => \App\Libraries\Helpers\Utility::getValueByLocale($sameGroupPage, 'slug')]) }}" class="disabled">{{ $i . '. ' . \App\Libraries\Helpers\Utility::getValueByLocale($sameGroupPage, 'name') }}</a></li>
                                                    @endif

                                                    <?php
                                                    $i ++;
                                                    ?>
                                                @endforeach

                                            </ul>
                                        </div>
                                        <div class="col-lg-9">
                                            <div class="tab-content">
                                                <div class="tab-pane active">
                                                    <div class="container-fluid">
                                                        <div class="row">
                                                            <div class="col-lg-12">
                                                                <h3>{{ \App\Libraries\Helpers\Utility::getValueByLocale($page, 'name') }}</h3>

                                                                @if(!empty($page->image))
                                                                    <img src="{{ $page->image }}" alt="{{ \App\Libraries\Helpers\Utility::getValueByLocale($page, 'name') }}" class="img-responsive w100p mb30">
                                                                @endif

                                                                <?php
                                                                echo \App\Libraries\Helpers\Utility::getValueByLocale($page, 'content');
                                                                ?>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="clearfix"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>

@stop
