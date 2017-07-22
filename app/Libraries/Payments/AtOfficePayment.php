<?php

namespace App\Libraries\Payments;

use App\Models\PaymentMethod;

class AtOfficePayment extends Payment
{
    public function getCode()
    {
        return 'at_office';
    }

    public function getName($lang = null)
    {
        $names = [
            'en' => 'Pay directly at the office of caydenthan.vn',
        ];

        if($lang !== null && isset($names[$lang]))
            return $names[$lang];

        return 'Đóng tiền trực tiếp tại văn phòng của caydenthan.vn';
    }

    public function getType()
    {
        return PaymentMethod::PAYMENT_TYPE_AT_OFFICE_DB;
    }

    public function renderView($paymentMethod)
    {
        echo view('libraries.payments.at_office_form', [
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