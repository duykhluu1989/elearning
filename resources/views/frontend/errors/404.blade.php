@extends('frontend.layouts.blank')

@section('page_heading', 404)

@section('section')

    @include('frontend.layouts.blankPartials.header')

    @include('frontend.layouts.partials.headtext')

    <main>
        <section class="bg_gray">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="main_content mb60">
                            <div class="row">
                                <div class="col-lg-12">
                                    <h3 class="text-center">404</h3>
                                    <p>@lang('theme.not_found')</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>

@stop