@extends('frontend.layouts.main')

@section('page_heading', trans('theme.order_success'))

@section('section')

    @include('frontend.layouts.partials.header')

    @include('frontend.layouts.partials.headtext')

    <main>
        <section class="bg_gray">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="main_content mb60">
                            <div class="row">
                                <div class="col-lg-12">
                                    <h3 class="text-center">
                                        @if(session('messageError'))
                                            @lang('theme.order_fail')
                                        @else
                                            @lang('theme.order_success')
                                        @endif
                                    </h3>
                                    <p>@lang('theme.order_number'): {{ $orderThankYou['order_number'] }}</p>
                                    <p>@lang('theme.payment_method'): {{ $orderThankYou['payment_method'] }}</p>
                                    <p>@lang('theme.total_price'): {{ \App\Libraries\Helpers\Utility::formatNumber($orderThankYou['total_price']) . 'đ' }}</p>
                                    @foreach($orderThankYou['courses'] as $course)
                                        <p>• {{ $course }}</p>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>

@stop