<?php

namespace App\Libraries\Payments;

use Illuminate\Support\Facades\Validator;
use App\Models\PaymentMethod;

class CodPayment extends Payment
{
    public function getCode()
    {
        return 'cod';
    }

    public function getName($lang = null)
    {
        $names = [
            'en' => 'COD',
        ];

        if($lang !== null && isset($names[$lang]))
            return $names[$lang];

        return 'Thu tiền tận nơi';
    }

    public function getType()
    {
        return PaymentMethod::PAYMENT_TYPE_COD_DB;
    }

    public function validatePlaceOrder($paymentMethod, $inputs, $validator, $cart)
    {
        $paymentValidator = Validator::make($inputs, [
            'name' => 'required|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|numeric',
            'address' => 'required|max:255',
            'province' => 'required',
            'district' => 'required',
        ]);

        if($paymentValidator->fails())
            $validator->errors()->merge($paymentValidator->errors());
    }
}