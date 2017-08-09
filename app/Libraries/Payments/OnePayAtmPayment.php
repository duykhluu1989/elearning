<?php

namespace App\Libraries\Payments;

use Illuminate\Support\Facades\Validator;
use App\Models\PaymentMethod;

class OnePayAtmPayment extends Payment
{
    const CODE = 'one_pay_atm';

    public function getCode()
    {
        return self::CODE;
    }

    public function getName($lang = null)
    {
        $names = [
            'en' => 'Payment by ATM card with Internet Banking (via Onepay)',
        ];

        if($lang !== null && isset($names[$lang]))
            return $names[$lang];

        return 'Thanh toán bằng thẻ ATM có đăng ký Internet Banking (qua cổng Onepay)';
    }

    public function getType()
    {
        return PaymentMethod::PAYMENT_TYPE_ATM_ONLINE_DB;
    }

    public function renderView($paymentMethod)
    {
        echo view('libraries.payments.one_pay_atm_form', [
            'paymentMethod' => $paymentMethod,
        ]);
    }
}