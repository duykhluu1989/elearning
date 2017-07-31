@extends('frontend.layouts.main')

@section('page_heading', trans('theme.account'))

@section('section')

    @include('frontend.layouts.partials.header')

    @include('frontend.layouts.partials.headtext')

    @include('frontend.layouts.partials.breabcrumb', ['breabcrumbTitle' => trans('theme.account')])

    <main>
        <section class="khoahoc bg_gray">
            <div class="container">
                <div class="row">
                    <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
                        <div class="navleft">
                            <h5>@lang('theme.account')</h5>

                            @include('frontend.users.partials.navigation')

                        </div>
                    </div>
                    <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                        <div class="main_content">
                            @if($orders->total() > 0)
                                <div class="table-responsive">
                                    <table class="table table-hover table-bordered">
                                        <thead>
                                        <tr>
                                            <th>@lang('theme.order_number')</th>
                                            <th>@lang('theme.total_price')</th>
                                            <th>@lang('theme.status')</th>
                                            <th>@lang('theme.time')</th>
                                            <th>@lang('theme.course')</th>
                                        </tr>
                                        </thead>
                                        <tbody>

                                        @foreach($orders as $order)
                                            <tr>
                                                <td>{{ $order->number }}</td>
                                                <td>{{ \App\Libraries\Helpers\Utility::formatNumber($order->total_price) . 'đ' }}</td>
                                                <td>
                                                    @if(!empty($order->cancelled_at))
                                                        @lang('theme.cancelled')
                                                    @elseif($order->payment_status == \App\Models\Order::PAYMENT_STATUS_PENDING_DB)
                                                        @lang('theme.unpaid')
                                                    @else
                                                        @lang('theme.paid')
                                                    @endif
                                                </td>
                                                <td>{{ $order->created_at }}</td>
                                                <td>
                                                    @foreach($order->orderItems as $orderItem)
                                                        {{ '- ' . \App\Libraries\Helpers\Utility::getValueByLocale($orderItem->course, 'name') }}
                                                        <br />
                                                    @endforeach
                                                </td>
                                            </tr>
                                        @endforeach

                                        </tbody>
                                    </table>
                                </div>
                                <div class="row">
                                    <div class="col-lg-12 text-center">
                                        <ul class="pagination">
                                            @if($orders->lastPage() > 1)
                                                @if($orders->currentPage() > 1)
                                                    <li><a href="{{ $orders->previousPageUrl() }}">&laquo;</a></li>
                                                    <li><a href="{{ $orders->url(1) }}">1</a></li>
                                                @endif

                                                @for($i = 2;$i >= 1;$i --)
                                                    @if($orders->currentPage() - $i > 1)
                                                        @if($orders->currentPage() - $i > 2 && $i == 2)
                                                            <li>...</li>
                                                            <li><a href="{{ $orders->url($orders->currentPage() - $i) }}">{{ $orders->currentPage() - $i }}</a></li>
                                                        @else
                                                            <li><a href="{{ $orders->url($orders->currentPage() - $i) }}">{{ $orders->currentPage() - $i }}</a></li>
                                                        @endif
                                                    @endif
                                                @endfor

                                                <li class="active"><a href="javascript:void(0)">{{ $orders->currentPage() }}</a></li>

                                                @for($i = 1;$i <= 2;$i ++)
                                                    @if($orders->currentPage() + $i < $orders->lastPage())
                                                        @if($orders->currentPage() + $i < $orders->lastPage() - 1 && $i == 2)
                                                            <li><a href="{{ $orders->url($orders->currentPage() + $i) }}">{{ $orders->currentPage() + $i }}</a></li>
                                                            <li>...</li>
                                                        @else
                                                            <li><a href="{{ $orders->url($orders->currentPage() + $i) }}">{{ $orders->currentPage() + $i }}</a></li>
                                                        @endif
                                                    @endif
                                                @endfor

                                                @if($orders->currentPage() < $orders->lastPage())
                                                    <li><a href="{{ $orders->url($orders->lastPage()) }}">{{ $orders->lastPage() }}</a></li>
                                                    <li><a href="{{ $orders->nextPageUrl() }}">&raquo;</a></li>
                                                @endif
                                            @endif
                                        </ul>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>

@stop