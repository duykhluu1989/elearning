<?php

namespace App\Libraries\Payments;

use App\Models\PaymentMethod;

class OnePayCreditPayment extends OnePayAtmPayment
{
    const CODE = 'one_pay_credit';

    public function getCode()
    {
        return self::CODE;
    }

    public function getName($lang = null)
    {
        $names = [
            'en' => 'Payment by Visa / Mastercard (via Onepay)',
        ];

        if($lang !== null && isset($names[$lang]))
            return $names[$lang];

        return 'Thanh toán bằng thẻ quốc tế Visa / Mastercard (qua cổng Onepay)';
    }

    public function getType()
    {
        return PaymentMethod::PAYMENT_TYPE_CREDIT_ONLINE_DB;
    }
}