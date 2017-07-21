<?php

namespace App\Libraries\Payments;

use App\Models\PaymentMethod;

class BankTransferPayment extends Payment
{
    public function getCode()
    {
        return 'bank_transfer';
    }

    public function getName($lang = null)
    {
        $names = [
            'en' => 'Transfer via bank or nearest ATM',
        ];

        if($lang !== null && isset($names[$lang]))
            return $names[$lang];

        return 'Chuyển khoản qua ngân hàng hoặc nộp tại cây ATM gần nhất';
    }

    public function getType()
    {
        return PaymentMethod::PAYMENT_TYPE_BANK_TRANSFER_DB;
    }

    public function renderView($paymentMethod)
    {
        echo view('libraries.payments.bank_transfer_form', [
            'paymentMethod' => $paymentMethod,
        ]);
    }

    public function validateAndSetData($paymentMethod, $inputs, $validator)
    {

    }
}