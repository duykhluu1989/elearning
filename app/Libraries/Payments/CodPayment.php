<?php

namespace App\Libraries\Payments;

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

    public function validatePlaceOrder($paymentMethod, $inputs, $validator)
    {
        if(empty($inputs['name']))
            $validator->errors()->add('name', trans('validation.required', ['attribute' => trans('theme.name')]));

        if(empty($inputs['email']))
            $validator->errors()->add('email', trans('validation.required', ['attribute' => 'email']));

        if(empty($inputs['phone']))
            $validator->errors()->add('phone', trans('validation.required', ['attribute' => trans('theme.phone')]));

        if(empty($inputs['address']))
            $validator->errors()->add('address', trans('validation.required', ['attribute' => trans('theme.address')]));

        if(empty($inputs['province']))
            $validator->errors()->add('province', trans('validation.required', ['attribute' => trans('theme.province')]));

        if(empty($inputs['district']))
            $validator->errors()->add('district', trans('validation.required', ['attribute' => trans('theme.district')]));
    }
}