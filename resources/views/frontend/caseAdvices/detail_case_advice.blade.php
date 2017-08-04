@extends('frontend.layouts.main')

@section('page_heading', \App\Libraries\Helpers\Utility::getValueByLocale($advice, 'name'))

@section('section')

    @include('frontend.layouts.partials.header')

    @include('frontend.layouts.partials.headtext')

    @include('frontend.caseAdvices.partials.case_advice_breadcrumb')

    <main>
        <section class="bg_gray">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="main_content mb60">
                            <h2>{{ \App\Libraries\Helpers\Utility::getValueByLocale($advice, 'name') }}</h2>
                            <p>{{ \App\Libraries\Helpers\Utility::getValueByLocale($advice, 'description') }}</p>
                            <div class="panel-group" id="accordion">

                                <?php
                                $i = 1;
                                ?>
                                @foreach($advice->caseAdviceSteps as $caseAdviceStep)
                                    <div class="panel panel-default">
                                        <div class="panel-heading">
                                            <h4 class="panel-title">
                                                <a data-toggle="collapse" data-parent="#accordion" href="#CaseAdviceStep_{{ $caseAdviceStep->step }}">@lang('theme.step') {{ $caseAdviceStep->step }}</a>
                                            </h4>
                                        </div>
                                        <div id="CaseAdviceStep_{{ $caseAdviceStep->step }}" class="panel-collapse collapse{{ $i == 1 ? ' in' : '' }}">
                                            <div class="panel-body">
                                                <p>{{ \App\Libraries\Helpers\Utility::getValueByLocale($caseAdviceStep, 'content') }}</p>

                                                @if($caseAdviceStep->type == \App\Models\CaseAdviceStep::TYPE_CHARGE_DB && !empty($advice->phone))
                                                    <h4>@lang('theme.advice_call', ['phone' => $advice->phone]) {{ $advice->adviser }}</h4>
                                                @endif
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
