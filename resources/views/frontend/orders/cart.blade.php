<div class="btnCart">
    <span class="sum">{{ $cart['countItem'] }}</span>

    @if($cart['countItem'] > 0)
        <div class="box_cart">
            <div class="arrow_top"></div>
            <div class="box_cart_content">
                @foreach($cart['cartItems'] as $cartItem)
                    <div class="row pro_item">
                        <div class="col-xs-3">
                            <a href="{{ action('Frontend\CourseController@detailCourse', ['id' => $cartItem->id, 'slug' => \App\Libraries\Helpers\Utility::getValueByLocale($cartItem, 'slug')]) }}"><img src="{{ $cartItem->image }}" alt="{{ \App\Libraries\Helpers\Utility::getValueByLocale($cartItem, 'name') }}" class="img-responsive"></a>
                        </div>
                        <div class="col-xs-9">
                            <p>{{ \App\Libraries\Helpers\Utility::getValueByLocale($cartItem, 'name') }}</p>
                            <p class="price">
                                @if($cartItem->validatePromotionPrice())
                                    {{ \App\Libraries\Helpers\Utility::formatNumber($cartItem->promotionPrice->price) . 'đ' }}
                                    <?php
                                    $cart['totalPrice'] += $cartItem->promotionPrice->price;
                                    ?>
                                @else
                                    {{ \App\Libraries\Helpers\Utility::formatNumber($cartItem->price) . 'đ' }}
                                    <?php
                                    $cart['totalPrice'] += $cartItem->price;
                                    ?>
                                @endif
                            </p>
                        </div>
                    </div>
                @endforeach
            </div>
            <div class="row row_tongtien">
                <div class="col-xs-6">
                    <p>@lang('theme.total_price')</p>
                </div>
                <div class="col-xs-6">
                    <p class="price pull-right">{{ \App\Libraries\Helpers\Utility::formatNumber($cart['totalPrice']) . 'đ' }}</p>
                </div>
            </div>
            <div class="row row_cart_bottom">
                <div class="col-xs-6">
                    <a href="{{ action('Frontend\OrderController@editCart') }}" class="btn btn-block btnGiohang">@lang('theme.cart')</a>
                </div>
                <div class="col-xs-6">
                    <a href="thanhtoan.php" class="btn btn-block btnThanhtoan">THANH TOÁN</a>
                </div>
            </div>
        </div>
    @endif

</div>