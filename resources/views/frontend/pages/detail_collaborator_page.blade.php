@extends('frontend.layouts.main')

@section('og_description', \App\Libraries\Helpers\Utility::getValueByLocale($page, 'short_description'))

@section('og_image', $page->image)

@section('page_heading', \App\Libraries\Helpers\Utility::getValueByLocale($page, 'name'))

@section('section')

    @include('frontend.layouts.partials.header')

    @include('frontend.layouts.partials.breabcrumb', ['breabcrumbTitle' => \App\Libraries\Helpers\Utility::getValueByLocale($page, 'name')])

    <section class="banner_cvtPP">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="display_table mh400">
                        <div class="table_content">
                            <h1>@lang('theme.become_collaborator')</h1>
                            <p>@lang('theme.collaborator_slogan')</p>

                            @if(auth()->guest() || empty(auth()->user()->collaboratorInformation))
                                <a href="javascript:void(0)" class="btn btn-lg btnRed">@lang('theme.sign_up')</a>
                            @endif

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <main>
        <section class="khoahoc bg_gray">
            <div class="container">
                <div class="row">
                    <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
                        <div class="navleft">
                            <ul class="list_navLeft">

                               @foreach($sameGroupPages as $sameGroupPage)
                                    @if($sameGroupPage->id == $page->id)
                                        <li class="active"><a href="javascript:void(0)">{{ \App\Libraries\Helpers\Utility::getValueByLocale($sameGroupPage, 'name') }}</a></li>
                                    @else
                                        <li><a href="{{ action('Frontend\PageController@detailPage', ['id' => $sameGroupPage->id, 'slug' => \App\Libraries\Helpers\Utility::getValueByLocale($sameGroupPage, 'slug')]) }}">{{ \App\Libraries\Helpers\Utility::getValueByLocale($sameGroupPage, 'name') }}</a></li>
                                    @endif
                                @endforeach

                            </ul>
                        </div>
                    </div>
                    <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                        <div class="main_content">
                            <h2>{{ \App\Libraries\Helpers\Utility::getValueByLocale($page, 'name') }}</h2>

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
        </section>
    </main>

@stop
