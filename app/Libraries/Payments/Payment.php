<?php

namespace App\Libraries\Payments;

use App\Libraries\Helpers\Utility;
use App\Models\PaymentMethod;

abstract class Payment
{
    protected static $payments;

    public static function getPayments($code = null)
    {
        if(empty(self::$payments))
        {
            $codPayment = new CodPayment();
            $bankTransferPayment = new BankTransferPayment();
            $atOfficePayment = new AtOfficePayment();

            self::$payments = [
                $codPayment->getCode() => $codPayment,
                $bankTransferPayment->getCode() => $bankTransferPayment,
                $atOfficePayment->getCode() => $atOfficePayment,
            ];
        }

        if($code !== null && isset(self::$payments[$code]))
            return self::$payments[$code];

        return self::$payments;
    }

    abstract public function getCode();

    abstract public function getName($lang = null);

    abstract public function getType();

    public function renderView($paymentMethod)
    {

    }

    public function validateAndSetData($paymentMethod, $inputs, $validator)
    {

    }

    public function validatePlaceOrder($paymentMethod, $inputs, $validator, $cart)
    {

    }

    public function initData()
    {
        $paymentMethod = new PaymentMethod();
        $paymentMethod->code = $this->getCode();
        $paymentMethod->name = $this->getName();
        $paymentMethod->name_en = $this->getName('en');
        $paymentMethod->type = $this->getType();
        $paymentMethod->status = Utility::ACTIVE_DB;
        $paymentMethod->order = 1;
        $paymentMethod->save();

        return $paymentMethod;
    }
}