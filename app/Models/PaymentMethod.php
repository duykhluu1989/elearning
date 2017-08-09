<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Libraries\Payments\Payment;

class PaymentMethod extends Model
{
    const PAYMENT_TYPE_COD_DB = 0;
    const PAYMENT_TYPE_BANK_TRANSFER_DB = 1;
    const PAYMENT_TYPE_AT_OFFICE_DB = 2;
    const PAYMENT_TYPE_POINT_DB = 3;
    const PAYMENT_TYPE_ATM_ONLINE_DB = 4;
    const PAYMENT_TYPE_CREDIT_ONLINE_DB = 5;

    protected $table = 'payment_method';

    public $timestamps = false;

    public static function initCorePaymentMethods()
    {
        $payments = Payment::getPayments();

        $paymentCodes = array_keys($payments);

        $paymentMethods = PaymentMethod::whereIn('code', $paymentCodes)->get();

        foreach($paymentMethods as $paymentMethod)
        {
            if(isset($payments[$paymentMethod->code]))
                unset($payments[$paymentMethod->code]);
        }

        foreach($payments as $payment)
            $paymentMethods[] = $payment->initData();
    }
}