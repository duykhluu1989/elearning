@extends('frontend.layouts.main')

@section('page_heading', trans('theme.case_advice'))

@section('section')

    @include('frontend.layouts.partials.header')

    @include('frontend.layouts.partials.headtext')

    @include('frontend.layouts.partials.breabcrumb', ['breabcrumbTitle' => trans('theme.case_advice')])

    <main>
        <section class="khoahoc bg_gray">
            <div class="container">
                <div class="row">
                    <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
                        <div class="navleft">
                            <h5>@lang('theme.case_advice')</h5>
                            <hr>
                            <ul class="list_navLeft">
                                <li class="{{ $type == str_slug(\App\Models\CaseAdvice::TYPE_ECONOMY_LABEL) ? 'active' : '' }}">
                                    <div class="row">
                                        <div class="col-xs-12">
                                            <a href="{{ action('Frontend\CaseAdviceController@adminCaseAdvice', ['type' => str_slug(\App\Models\CaseAdvice::TYPE_ECONOMY_LABEL)]) }}">@lang('theme.economy')</a>
                                        </div>
                                    </div>
                                </li>
                                <li class="{{ $type == str_slug(\App\Models\CaseAdvice::TYPE_LAW_LABEL) ? 'active' : '' }}">
                                    <div class="row">
                                        <div class="col-xs-12">
                                            <a href="{{ action('Frontend\CaseAdviceController@adminCaseAdvice', ['type' => str_slug(\App\Models\CaseAdvice::TYPE_LAW_LABEL)]) }}">@lang('theme.law')</a>
                                        </div>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                        <div class="main_content">

                            @foreach($advices as $advice)
                                <div class="row item_news">
                                    <div class="col-lg-12">
                                        <h4><a href="{{ action('Frontend\CaseAdviceController@detailCaseAdvice', ['id' => $advice->id, 'slug' => \App\Libraries\Helpers\Utility::getValueByLocale($advice, 'slug')]) }}">{{ \App\Libraries\Helpers\Utility::getValueByLocale($advice, 'name') }}</a></h4>
                                        <p>{{ \App\Libraries\Helpers\Utility::getValueByLocale($advice, 'description') }}</p>
                                    </div>
                                </div>
                            @endforeach

                            <div class="row">
                                <div class="col-lg-12 text-center">
                                    <ul class="pagination">
                                        @if($advices->lastPage() > 1)
                                            @if($advices->currentPage() > 1)
                                                <li><a href="{{ $advices->previousPageUrl() }}">&laquo;</a></li>
                                                <li><a href="{{ $advices->url(1) }}">1</a></li>
                                            @endif

                                            @for($i = 2;$i >= 1;$i --)
                                                @if($advices->currentPage() - $i > 1)
                                                    @if($advices->currentPage() - $i > 2 && $i == 2)
                                                        <li>...</li>
                                                        <li><a href="{{ $advices->url($advices->currentPage() - $i) }}">{{ $advices->currentPage() - $i }}</a></li>
                                                    @else
                                                        <li><a href="{{ $advices->url($advices->currentPage() - $i) }}">{{ $advices->currentPage() - $i }}</a></li>
                                                    @endif
                                                @endif
                                            @endfor

                                            <li class="active"><a href="javascript:void(0)">{{ $advices->currentPage() }}</a></li>

                                            @for($i = 1;$i <= 2;$i ++)
                                                @if($advices->currentPage() + $i < $advices->lastPage())
                                                    @if($advices->currentPage() + $i < $advices->lastPage() - 1 && $i == 2)
                                                        <li><a href="{{ $advices->url($advices->currentPage() + $i) }}">{{ $advices->currentPage() + $i }}</a></li>
                                                        <li>...</li>
                                                    @else
                                                        <li><a href="{{ $advices->url($advices->currentPage() + $i) }}">{{ $advices->currentPage() + $i }}</a></li>
                                                    @endif
                                                @endif
                                            @endfor

                                            @if($advices->currentPage() < $advices->lastPage())
                                                <li><a href="{{ $advices->url($advices->lastPage()) }}">{{ $advices->lastPage() }}</a></li>
                                                <li><a href="{{ $advices->nextPageUrl() }}">&raquo;</a></li>
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