<?php

namespace App\Libraries\Payments;

use App\Libraries\Helpers\Utility;
use App\Models\PaymentMethod;
use App\Models\Course;

class PointPayment extends Payment
{
    const CODE = 'point';

    public function getCode()
    {
        return self::CODE;
    }

    public function getName($lang = null)
    {
        $names = [
            'en' => 'Pay by point',
        ];

        if($lang !== null && isset($names[$lang]))
            return $names[$lang];

        return 'Thanh toán bằng điểm tích lũy';
    }

    public function getType()
    {
        return PaymentMethod::PAYMENT_TYPE_POINT_DB;
    }

    public function validatePlaceOrder($paymentMethod, $inputs, $validator, $cart)
    {
        if(!empty($inputs['discount_code']))
        {
            $validator->errors()->add('payment_method', trans('theme.pay_by_point_can_not_discount'));

            return false;
        }

        if(empty(auth()->user()->studentInformation) || empty(auth()->user()->studentInformation->current_point))
        {
            $validator->errors()->add('payment_method', trans('theme.not_enough_point'));

            return false;
        }

        $courses = Course::select('id', 'name', 'name_en', 'point_price')
            ->whereIn('id', $cart->cartItems)
            ->get();

        $totalPointPrice = 0;

        foreach($courses as $course)
        {
            if(empty($course->point_price))
            {
                $validator->errors()->add('payment_method', trans('theme.can_not_buy_by_point', ['course' => Utility::getValueByLocale($course, 'name')]));

                return false;
            }
            else
                $totalPointPrice += $course->point_price;
        }

        if(auth()->user()->studentInformation->current_point < $totalPointPrice)
            $validator->errors()->add('payment_method', trans('theme.not_enough_point'));
    }

    public function handlePlacedOrderPayment($paymentMethod, $order)
    {
        $paid = $order->completePayment(null, true);

        if($paid == true)
        {
            $order->user->studentInformation->current_point -= $order->total_point_price;
            $order->user->studentInformation->save();
        }
    }
}