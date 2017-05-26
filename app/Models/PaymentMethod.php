<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaymentMethod extends Model
{
    const STATUS_ACTIVE_DB = 1;
    const STATUS_INACTIVE_DB = 0;
    const STATUS_ACTIVE_LABEL = 'Kích Hoạt';
    const STATUS_INACTIVE_LABEL = 'Vô Hiệu';

    protected $table = 'payment_method';

    public $timestamps = false;

    public static function getPaymentMethodStatus($value = null)
    {
        $status = [
            self::STATUS_ACTIVE_DB => self::STATUS_ACTIVE_LABEL,
            self::STATUS_INACTIVE_DB => self::STATUS_INACTIVE_LABEL,
        ];

        if($value !== null && isset($status[$value]))
            return $status[$value];

        return $status;
    }
}