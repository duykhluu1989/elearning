<?php

namespace App\Libraries\Payments;

use App\Models\PaymentMethod;

class BankTransferPayment extends Payment
{
    const CODE = 'bank_transfer';

    public function getCode()
    {
        return self::CODE;
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
        if(isset($inputs['detail']))
        {
            $details = array();

            foreach($inputs['detail'] as $attribute => $attributeItems)
            {
                foreach($attributeItems as $key => $item)
                {
                    if(!empty($item))
                        $details[$key][$attribute] = $item;
                }
            }

            $paymentMethod->detail = json_encode($details);
        }
        else
            $paymentMethod->detail = null;
    }
}