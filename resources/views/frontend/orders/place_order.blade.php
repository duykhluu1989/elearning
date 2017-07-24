@extends('frontend.layouts.main')

@section('page_heading', trans('theme.checkout'))

@section('section')

    @include('frontend.layouts.partials.header')

    @include('frontend.layouts.partials.breabcrumb', ['breabcrumbTitle' => trans('theme.checkout')])

    <main>
        <section class="khoahoc bg_gray">
            <div class="container">
                <form action="{{ action('Frontend\OrderController@placeOrder') }}" method="post">
                    <div class="row">
                        <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
                            <div class="khoahoc_dachon">
                                <p>{{ $cart['countItem'] }} @lang('theme.choose_course')</p>
                                @if($errors->has('cart'))
                                    <div class="form-group has-error">
                                        <span class="help-block">* {{ $errors->first('cart') }}</span>
                                    </div>
                                @endif

                                @if($cart['countItem'] > 0)
                                    @foreach($cart['cartItems'] as $cartItem)
                                        <div class="row item_khoahoc_dachon">
                                            <div class="col-xs-4">
                                                <a href="{{ action('Frontend\CourseController@detailCourse', ['id' => $cartItem->id, 'slug' => \App\Libraries\Helpers\Utility::getValueByLocale($cartItem, 'slug')]) }}"><img src="{{ $cartItem->image }}" alt="{{ \App\Libraries\Helpers\Utility::getValueByLocale($cartItem, 'name') }}" class="img-responsive"></a>
                                            </div>
                                            <div class="col-xs-8 pl0 pr0">
                                                <p><a href="{{ action('Frontend\CourseController@detailCourse', ['id' => $cartItem->id, 'slug' => \App\Libraries\Helpers\Utility::getValueByLocale($cartItem, 'slug')]) }}">{{ \App\Libraries\Helpers\Utility::getValueByLocale($cartItem, 'name') }}</a></p>
                                                <p class="price">
                                                    @if($cartItem->validatePromotionPrice())
                                                        {{ \App\Libraries\Helpers\Utility::formatNumber($cartItem->promotionPrice->price) . 'đ' }}
                                                    @else
                                                        {{ \App\Libraries\Helpers\Utility::formatNumber($cartItem->price) . 'đ' }}
                                                    @endif
                                                </p>
                                            </div>
                                        </div>
                                    @endforeach
                                @endif

                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="frm_maKM">
                                            <div class="form-group">
                                                <input type="text" class="form-control" name="discount_code" placeholder="@lang('theme.input_discount_code')" value="{{ old('discount_code') }}" />
                                                @if($errors->has('discount_code'))
                                                    <div class="form-group has-error">
                                                        <span class="help-block">* {{ $errors->first('discount_code') }}</span>
                                                    </div>
                                                @endif
                                            </div>
                                            <button type="button" id="UseDiscountCodeButton" class="btn btn-lg btn-block btnRed">@lang('theme.use_discount_code')</button>
                                        </div>
                                        <div class="table-responsive table_hocphi">
                                            <table class="table table-hover">
                                                <tbody>
                                                <tr>
                                                    <th>@lang('theme.total_item_price')</th>
                                                    <td id="OrderTotalItemPrice" data-order-total-item-price="{{ $cart['totalPrice'] }}">{{ \App\Libraries\Helpers\Utility::formatNumber($cart['totalPrice']) . 'đ' }}</td>
                                                </tr>
                                                <tr>
                                                    <th>@lang('theme.discount')</th>
                                                    <td id="OrderDiscountPrice">0đ</td>
                                                </tr>
                                                <tr>
                                                    <th>@lang('theme.total_price')</th>
                                                    <td class="OrderTotalPrice">{{ \App\Libraries\Helpers\Utility::formatNumber($cart['totalPrice']) . 'đ' }}</td>
                                                </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                            <div class="main_content">
                                <p>@lang('theme.payment_method')</p>
                                <p>@lang('theme.bill'): <b>
                                        <span class="OrderTotalPrice">{{ \App\Libraries\Helpers\Utility::formatNumber($cart['totalPrice']) . 'đ' }}</span>
                                        @if(app()->getLocale() == 'en')
                                            <span id="OrderTotalPriceEn">({{ round($cart['totalPrice'] / \App\Models\Setting::getSettings(\App\Models\Setting::CATEGORY_GENERAL_DB, \App\Models\Setting::EXCHANGE_USD_RATE), 2) . 'USD' }})</span>
                                        @endif
                                    </b></p>
                                @if($errors->has('payment_method'))
                                    <div class="form-group has-error">
                                        <span class="help-block">* {{ $errors->first('payment_method') }}</span>
                                    </div>
                                @endif
                                <div class="panel-group" id="accordion">

                                    <?php
                                    $choosePaymentMethod = old('payment_method');
                                    $i = 1;
                                    ?>
                                    @foreach($paymentMethods as $paymentMethod)
                                        <?php
                                        if($i == 1 && empty($choosePaymentMethod))
                                            $choosePaymentMethod = $paymentMethod->id;
                                        ?>

                                        <div class="panel panel-default">
                                            <div class="panel-heading">
                                                <h4 class="panel-title">
                                                    <a data-toggle="collapse" data-parent="#accordion" href="#PaymentMethod_{{ $paymentMethod->id }}" class="ChoosePaymentMethod" data-payment-method-id="{{ $paymentMethod->id }}">{{ \App\Libraries\Helpers\Utility::getValueByLocale($paymentMethod, 'name') }}</a>
                                                </h4>
                                            </div>
                                            <div id="PaymentMethod_{{ $paymentMethod->id }}" class="panel-collapse collapse{{ (($i == 1 && empty($choosePaymentMethod)) || $choosePaymentMethod == $paymentMethod->id) ? ' in' : '' }}">
                                                <div class="panel-body">

                                                    <?php
                                                    switch($paymentMethod->type)
                                                    {
                                                        case \App\Models\PaymentMethod::PAYMENT_TYPE_COD_DB:
                                                            ?>

                                                        <div class="form-group">
                                                            <div class="row">
                                                                <div class="col-lg-6">
                                                                    <input type="text" class="form-control" name="name" placeholder="* @lang('theme.name')" value="{{ old('name') }}" />
                                                                    @if($errors->has('name'))
                                                                        <div class="form-group has-error">
                                                                            <span class="help-block">* {{ $errors->first('name') }}</span>
                                                                        </div>
                                                                    @endif
                                                                </div>
                                                                <div class="col-lg-6">
                                                                    <input type="text" class="form-control" name="email" placeholder="* Email" value="{{ old('email') }}" />
                                                                    @if($errors->has('email'))
                                                                        <div class="form-group has-error">
                                                                            <span class="help-block">* {{ $errors->first('email') }}</span>
                                                                        </div>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <div class="row">
                                                                <div class="col-lg-6">
                                                                    <input type="text" class="form-control" name="phone" placeholder="* @lang('theme.phone')" value="{{ old('phone') }}" />
                                                                    @if($errors->has('phone'))
                                                                        <div class="form-group has-error">
                                                                            <span class="help-block">* {{ $errors->first('phone') }}</span>
                                                                        </div>
                                                                    @endif
                                                                </div>
                                                                <div class="col-lg-6">
                                                                    <input type="text" class="form-control" name="address" placeholder="* @lang('theme.address')" value="{{ old('address') }}" />
                                                                    @if($errors->has('address'))
                                                                        <div class="form-group has-error">
                                                                            <span class="help-block">* {{ $errors->first('address') }}</span>
                                                                        </div>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <div class="row">
                                                                <div class="col-lg-6">
                                                                    <select id="OrderProvince" name="province" class="form-control">
                                                                        <?php
                                                                        $province = old('province');
                                                                        ?>

                                                                        <option value="">* @lang('theme.province')</option>

                                                                        @foreach(\App\Libraries\Helpers\Area::$provinces as $code => $data)
                                                                            @if($province == $code)
                                                                                <option selected="selected" value="{{ $code }}">{{ $data['name'] }}</option>
                                                                            @else
                                                                                <option value="{{ $code }}">{{ $data['name'] }}</option>
                                                                            @endif
                                                                        @endforeach
                                                                    </select>
                                                                    @if($errors->has('province'))
                                                                        <div class="form-group has-error">
                                                                            <span class="help-block">* {{ $errors->first('province') }}</span>
                                                                        </div>
                                                                    @endif
                                                                </div>
                                                                <div class="col-lg-6">
                                                                    <select id="OrderDistrict" name="district" class="form-control">
                                                                        <?php
                                                                        $district = old('district');
                                                                        ?>

                                                                        <option value="">* @lang('theme.district')</option>

                                                                        @if($district && isset(\App\Libraries\Helpers\Area::$provinces[$province]['cities']))
                                                                            @foreach(\App\Libraries\Helpers\Area::$provinces[$province]['cities'] as $code => $data)
                                                                                @if($district == $code)
                                                                                    <option selected="selected" value="{{ $code }}">{{ $data }}</option>
                                                                                @else
                                                                                    <option value="{{ $code }}">{{ $data }}</option>
                                                                                @endif
                                                                            @endforeach
                                                                        @endif
                                                                    </select>
                                                                    @if($errors->has('district'))
                                                                        <div class="form-group has-error">
                                                                            <span class="help-block">* {{ $errors->first('district') }}</span>
                                                                        </div>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <p>@lang('theme.input_require')</p>
                                                        <div class="form-group">
                                                            <textarea name="note" class="form-control" rows="8" placeholder="@lang('theme.note')">{{ old('note') }}</textarea>
                                                        </div>

                                                            <?php
                                                            break;

                                                        case \App\Models\PaymentMethod::PAYMENT_TYPE_BANK_TRANSFER_DB:
                                                            ?>

                                                        <p>* @lang('theme.payment_bank_text_1')</p>
                                                        <h4>@lang('theme.payment_bank_text_2')</h4>
                                                        <p>@lang('theme.payment_bank_text_3'):</p>

                                                        @if(!empty($paymentMethod->detail))
                                                            <?php
                                                            $details = json_decode($paymentMethod->detail, true);
                                                            ?>
                                                            @foreach($details as $detail)
                                                                <h4>@lang('theme.bank'): {{ isset($detail['bank']) ? $detail['bank'] : '' }}</h4>
                                                                <p>
                                                                    • @lang('theme.bank_number'): {{ isset($detail['bank_number']) ? $detail['bank_number'] : '' }}<br />
                                                                    • @lang('theme.bank_name'): {{ isset($detail['bank_name']) ? $detail['bank_name'] : '' }}<br />
                                                                </p>
                                                            @endforeach
                                                        @endif

                                                            <p>
                                                                @lang('theme.payment_bank_text_4'):<br />
                                                                • @lang('theme.payment_bank_text_5') <br>
                                                                • @lang('theme.example'): 0909090909 - Nguyen Thi Huong Lan - caydenthan@gmail.com - 2313123
                                                            </p>

                                                            <?php
                                                            break;

                                                        case \App\Models\PaymentMethod::PAYMENT_TYPE_AT_OFFICE_DB:
                                                            ?>

                                                            <p>@lang('theme.office_address'):</p>

                                                            @if(!empty($paymentMethod->detail))
                                                                <?php
                                                                $details = json_decode($paymentMethod->detail, true);
                                                                ?>
                                                                @foreach($details as $detail)
                                                                    <h4>{{ isset($detail['address']) ? $detail['address'] : '' }}</h4>
                                                                @endforeach
                                                            @endif


                                                            <?php
                                                            break;
                                                    }
                                                    ?>
                                                </div>
                                            </div>
                                        </div>
                                        <?php
                                        $i ++;
                                        ?>
                                    @endforeach
                                </div>

                                <input type="hidden" name="payment_method" value="{{ $choosePaymentMethod }}" />
                                <div class="row">
                                    <div class="col-xs-6">
                                        <a href="{{ action('Frontend\OrderController@editCart') }}" class="btn btn-lg btnYellow">@lang('theme.back_to_cart')</a>
                                    </div>
                                    <div class="col-xs-6">
                                        <button type="submit" class="btn btn-lg btnRed pull-right">@lang('theme.place_order')</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{ csrf_field() }}
                </form>
            </div>
        </section>
    </main>

@stop

@push('scripts')
    <script type="text/javascript">
        $('.ChoosePaymentMethod').click(function(e) {
            if($(this).data('payment-method-id') != '')
            {
                var paymentMethodInputElem = $('input[name="payment_method"]');

                if($(this).data('payment-method-id') == paymentMethodInputElem.val())
                {
                    e.stopImmediatePropagation();
                    e.preventDefault();
                }
                else
                    paymentMethodInputElem.val($(this).data('payment-method-id'));
            }
        });

        $('#UseDiscountCodeButton').click(function() {
            var discountCodeInputElem = $('input[name="discount_code"]');
            var discountCodeVal = discountCodeInputElem.val().trim();

            if(discountCodeVal != '')
            {
                $.ajax({
                    url: '{{ action('Frontend\OrderController@useDiscountCode') }}',
                    type: 'post',
                    data: '_token=' + $('input[name="_token"]').first().val() + '&discount_code=' + discountCodeVal,
                    success: function(result) {
                        if(result)
                        {
                            result = JSON.parse(result);

                            if(result['status'] == 'error')
                            {
                                swal({
                                    title: result['message'],
                                    type: 'error',
                                    confirmButtonClass: 'btn-success'
                                });
                            }
                            else
                            {
                                if(result['discount'] > 0)
                                {
                                    $('#OrderDiscountPrice').html(formatNumber(result['discount'].toString(), '.') + 'đ');

                                    var totalPriceAfterDiscount = $('#OrderTotalItemPrice').data('order-total-item-price') - result['discount'];

                                    $('.OrderTotalPrice').each(function() {
                                        $(this).html(formatNumber(totalPriceAfterDiscount.toString(), '.') + 'đ');
                                    });

                                    @if(app()->getLocale() == 'en')
                                        $('#OrderTotalPriceEn').html('(' +
                                            (totalPriceAfterDiscount / {{ \App\Models\Setting::getSettings(\App\Models\Setting::CATEGORY_GENERAL_DB, \App\Models\Setting::EXCHANGE_USD_RATE) }}).toFixed(2) +
                                            'USD' +
                                        ')');
                                    @endif
                                }
                            }
                        }
                    }
                });
            }
        });

        $('#OrderProvince').change(function() {
            var districtElem = $('#OrderDistrict');

            districtElem.html('' +
                '<option value="">* @lang('theme.district')</option>' +
            '');

            if($(this).val() != '')
            {
                $.ajax({
                    url: '{{ action('Frontend\OrderController@getListDistrict') }}',
                    type: 'get',
                    data: 'province_code=' + $(this).val(),
                    success: function(result) {
                        if(result)
                        {
                            result = JSON.parse(result);

                            for(var code in result)
                            {
                                if(result.hasOwnProperty(code))
                                {
                                    districtElem.append('' +
                                        '<option value="' + code + '">' + result[code] + '</option>' +
                                    '');
                                }
                            }
                        }
                    }
                });
            }
        });
    </script>
@endpush
