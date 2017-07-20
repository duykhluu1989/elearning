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
            'en' => '',
        ];

        if($lang !== null && isset($names[$lang]))
            return $names[$lang];

        return 'COD';
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