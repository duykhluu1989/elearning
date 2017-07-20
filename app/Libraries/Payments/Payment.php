<?php

namespace App\Libraries\Payments;

use App\Libraries\Helpers\Utility;
use App\Models\PaymentMethod;

abstract class Payment
{
    protected static $payments;

    public static function getPayments($code = null)
    {
        if(empty(static::$payments))
        {
            $codPayment = new CodPayment();

            static::$payments = [
                $codPayment->getCode() => $codPayment,
            ];
        }

        if($code !== null && isset(static::$payments[$code]))
            return static::$payments[$code];

        return static::$payments;
    }

    abstract public function getCode();

    abstract public function getName($lang = null);

    abstract public function getType();

    abstract public function renderView($paymentMethod);

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