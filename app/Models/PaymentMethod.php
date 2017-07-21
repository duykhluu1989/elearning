<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Libraries\Payments\Payment;

class PaymentMethod extends Model
{
    const PAYMENT_TYPE_COD_DB = 0;
    const PAYMENT_TYPE_BANK_TRANSFER_DB = 1;
    const PAYMENT_TYPE_COD_LABEL = 'Thu trực tiếp';
    const PAYMENT_TYPE_BANK_TRANSFER_LABEL = 'Chuyển khoản';

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

    public static function getPaymentMethodType($value = null)
    {
        $type = [
            self::PAYMENT_TYPE_COD_DB => self::PAYMENT_TYPE_COD_LABEL,
            self::PAYMENT_TYPE_BANK_TRANSFER_DB => self::PAYMENT_TYPE_BANK_TRANSFER_LABEL,
        ];

        if($value !== null && isset($type[$value]))
            return $type[$value];

        return $type;
    }
}