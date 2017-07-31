@extends('frontend.layouts.main')

@section('page_heading', trans('theme.cart'))

@section('section')

    @include('frontend.layouts.partials.header')

    @include('frontend.layouts.partials.headtext')

    @include('frontend.layouts.partials.breabcrumb', ['breabcrumbTitle' => trans('theme.cart')])

    <main>
        <section class="giohang">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="table-responsive table_giohang">
                            <table class="table table-hover table-condensed">
                                <thead>
                                <tr>
                                    <th width="60%">@lang('theme.course')</th>
                                    <th>@lang('theme.quantity')</th>
                                    <th>@lang('theme.price')</th>
                                    <th></th>
                                </tr>
                                </thead>
                                <tbody id="CartTableBody">

                                @if($cart['countItem'] > 0)
                                    @foreach($cart['cartItems'] as $cartItem)
                                        <tr>
                                            <td>
                                                <div class="row">
                                                    <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                                                        <img src="{{ $cartItem->image }}" alt="{{ \App\Libraries\Helpers\Utility::getValueByLocale($cartItem, 'name') }}" class="img-responsive">
                                                    </div>
                                                    <div class="col-lg-8 col-md-8 col-sm-12 col-xs-12">
                                                        <p>{{ \App\Libraries\Helpers\Utility::getValueByLocale($cartItem, 'name') }}</p>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <p>1</p>
                                            </td>
                                            <td>
                                                <p class="price">
                                                    @if($cartItem->validatePromotionPrice())
                                                        {{ \App\Libraries\Helpers\Utility::formatNumber($cartItem->promotionPrice->price) . 'đ' }}
                                                        <?php
                                                        $cartItemPrice = $cartItem->promotionPrice->price;
                                                        ?>
                                                    @else
                                                        {{ \App\Libraries\Helpers\Utility::formatNumber($cartItem->price) . 'đ' }}
                                                        <?php
                                                        $cartItemPrice = $cartItem->price;
                                                        ?>
                                                    @endif
                                                </p>
                                            </td>
                                            <td>
                                                <a href="javascript:void(0)" class="DeleteCartItem" data-course-id="{{ $cartItem->id }}" data-course-price="{{ $cartItemPrice }}"><i class="fa fa-times-circle fa-2x" aria-hidden="true"></i></a>
                                            </td>
                                        </tr>
                                    @endforeach

                                    <tr>
                                        <td colspan="2">
                                            <p>@lang('theme.total_price')</p>
                                        </td>
                                        <td colspan="2">
                                            <p class="price pull-right" id="CartTableTotalPrice" data-cart-total-price="{{ $cart['totalPrice'] }}">{{ \App\Libraries\Helpers\Utility::formatNumber($cart['totalPrice']) . 'đ' }}</p>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="4">
                                            <div class="row">
                                                <div class="col-xs-4">
                                                    <a href="{{ !empty($category) ? action('Frontend\CourseController@detailCategory', ['id' => $category->id, 'slug' => \App\Libraries\Helpers\Utility::getValueByLocale($category, 'slug')]) : action('Frontend\HomeController@home') }}" class="btn btn-block btnGiohang">@lang('theme.browse_course')</a>
                                                </div>
                                                <div class="col-xs-4"></div>
                                                <div class="col-xs-4">
                                                    <a href="{{ action('Frontend\OrderController@placeOrder') }}" class="btn btn-block btnThanhtoan">@lang('theme.checkout')</a>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @else
                                    <tr>
                                        <td colspan="4" class="text-center">@lang('theme.empty_cart')</td>
                                    </tr>
                                @endif

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

        </section>
    </main>

    @if($cart['countItem'] > 0)
        @push('scripts')
            <script type="text/javascript">
                $('.DeleteCartItem').click(function() {
                    if($(this).data('course-id') != '')
                    {
                        var elem = $(this);

                        $.ajax({
                            url: '{{ action('Frontend\OrderController@deleteCartItem') }}',
                            type: 'get',
                            data: 'course_id=' + $(this).data('course-id'),
                            success: function(result) {
                                if(result)
                                {
                                    if(result == 'Success')
                                    {
                                        elem.parent().parent().remove();
                                        var totalPriceTableElem = $('#CartTableTotalPrice');
                                        var totalPrice = totalPriceTableElem.data('cart-total-price');
                                        totalPrice -= elem.data('course-price');
                                        totalPriceTableElem.data('cart-total-price', totalPrice);
                                        totalPriceTableElem.html(formatNumber(totalPriceTableElem.data('cart-total-price').toString(), '.') + 'đ');

                                        $('#CartBoxItem_' + elem.data('course-id')).remove();
                                        var countItemBoxElem = $('#CartBoxCountItem');
                                        countItemBoxElem.html(countItemBoxElem.html() - 1);
                                        $('#CartBoxTotalPrice').html(formatNumber(totalPriceTableElem.data('cart-total-price').toString(), '.') + 'đ');
                                    }
                                    else if(result == 'Empty')
                                    {
                                        $('#CartTableBody').html('' +
                                            '<tr>' +
                                            '<td colspan="4" class="text-center">@lang('theme.empty_cart')</td>' +
                                            '</tr>' +
                                        '');

                                        $('#CartBoxCountItem').html(0);
                                        $('#CartBoxBody').remove();
                                    }
                                }
                            }
                        });
                    }
                });
            </script>
        @endpush
    @endif

@stop
