<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    const PAYMENT_STATUS_PENDING_DB = 0;
    const PAYMENT_STATUS_COMPLETE_DB = 1;
    const PAYMENT_STATUS_PENDING_LABEL = 'Chưa Thanh Toán';
    const PAYMENT_STATUS_COMPLETE_LABEL = 'Đã Thanh Toán';

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

    public function orderAddress()
    {
        return $this->hasOne('App\Models\OrderAddress', 'order_id');
    }

    public function orderTransactions()
    {
        return $this->hasMany('App\Models\OrderTransaction', 'order_id');
    }

    public static function getOrderPaymentStatus($value = null)
    {
        $status = [
            self::PAYMENT_STATUS_PENDING_DB => self::PAYMENT_STATUS_PENDING_LABEL,
            self::PAYMENT_STATUS_COMPLETE_DB => self::PAYMENT_STATUS_COMPLETE_LABEL,
        ];

        if($value !== null && isset($status[$value]))
            return $status[$value];

        return $status;
    }

    public function completePayment($note = null)
    {
        if(empty($this->cancelled_at) && $this->payment_status == self::PAYMENT_STATUS_PENDING_DB)
        {
            $this->payment_status = self::PAYMENT_STATUS_COMPLETE_DB;
            $this->save();

            $transaction = new OrderTransaction();
            $transaction->order_id = $this->id;
            $transaction->amount = $this->total_price;

            $transaction->type = self::PAYMENT_STATUS_COMPLETE_DB;
            $transaction->created_at = date('Y-m-d H:i:s');

            if($note !== null)
                $transaction->note = $note;

            $transaction->save();

            foreach($this->orderItems as $orderItem)
            {
                $userCourse = new UserCourse();
                $userCourse->user_id = $this->user_id;
                $userCourse->course_id = $orderItem->course_id;
                $userCourse->order_id = $this->id;
                $userCourse->course_item_tracking = 1;
                $userCourse->save();

                $orderItem->course->bought_count ++;
                $orderItem->course->save();
            }

            $pointEarn = round($transaction->amount / Setting::getSettings(Setting::CATEGORY_GENERAL_DB, Setting::EXCHANGE_POINT_RATE), 0, PHP_ROUND_HALF_DOWN);

            if(empty($this->user->studentInformation))
            {
                $student = new Student();
                $student->user_id = $this->user_id;
                $student->course_count = 1;
                $student->total_spent = $transaction->amount;
                $student->current_point = $pointEarn;
                $student->total_point = $pointEarn;
                $student->save();
            }
            else
            {
                $this->user->studentInformation->course_count += 1;
                $this->user->studentInformation->total_spent += $transaction->amount;
                $this->user->studentInformation->current_point += $pointEarn;
                $this->user->studentInformation->total_point += $pointEarn;
                $this->user->studentInformation->save();
            }

            return true;
        }

        return false;
    }
}