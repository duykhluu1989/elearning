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

    public function renderView($paymentMethod)
    {
        return null;
    }
}