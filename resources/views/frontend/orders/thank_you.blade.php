@extends('frontend.layouts.main')

@section('page_heading', trans('theme.checkout'))

@section('section')

    @include('frontend.layouts.partials.header')

    <main>
        <section class="bg_gray mb60">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="main_content">
                            <h2>@lang('theme.order_success')</h2>
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

        </section>
    </main>

@stop