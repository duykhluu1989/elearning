<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    const PAYMENT_STATUS_PENDING_DB = 0;
    const PAYMENT_STATUS_COMPLETE_DB = 1;
    const PAYMENT_STATUS_REFUND_DB = 2;
    const PAYMENT_STATUS_PENDING_LABEL = 'Chưa Thanh Toán';
    const PAYMENT_STATUS_COMPLETE_LABEL = 'Đã Thanh Toán';
    const PAYMENT_STATUS_REFUND_LABEL = 'Đã Hoàn Tiền';

    const ORDER_NUMBER_PREFIX = 1987654321;

    protected $table = 'order';

    public $timestamps = false;

    protected static function boot()
    {
        parent::boot();

        self::created(function(Order $order) {
            $order->number = self::ORDER_NUMBER_PREFIX + $order->id;
            $order->save();
        });
    }

    public function user()
    {
        return $this->belongsTo('App\Models\User', 'user_id');
    }

    public function paymentMethod()
    {
        return $this->belongsTo('App\Models\PaymentMethod', 'payment_method_id');
    }

    public function orderItems()
    {
        return $this->hasMany('App\Models\OrderItem', 'order_id');
    }

    public static function getOrderPaymentStatus($value = null)
    {
        $status = [
            self::PAYMENT_STATUS_PENDING_DB => self::PAYMENT_STATUS_PENDING_LABEL,
            self::PAYMENT_STATUS_COMPLETE_DB => self::PAYMENT_STATUS_COMPLETE_LABEL,
            self::PAYMENT_STATUS_REFUND_DB => self::PAYMENT_STATUS_REFUND_LABEL,
        ];

        if($value !== null && isset($status[$value]))
            return $status[$value];

        return $status;
    }
}