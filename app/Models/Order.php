<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Libraries\Helpers\Utility;

class Order extends Model
{
    const PAYMENT_STATUS_PENDING_DB = 0;
    const PAYMENT_STATUS_COMPLETE_DB = 1;
    const PAYMENT_STATUS_FAIL_DB = 2;
    const PAYMENT_STATUS_PENDING_LABEL = 'Chưa Thanh Toán';
    const PAYMENT_STATUS_COMPLETE_LABEL = 'Đã Thanh Toán';
    const PAYMENT_STATUS_FAIL_LABEL = 'Thanh Toán Thất Bại';

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

    public function discount()
    {
        return $this->belongsTo('App\Models\Discount', 'discount_id');
    }

    public function referral()
    {
        return $this->belongsTo('App\Models\User', 'referral_id');
    }

    public function collaboratorTransactions()
    {
        return $this->hasMany('App\Models\CollaboratorTransaction', 'order_id');
    }

    public static function getOrderPaymentStatus($value = null)
    {
        $status = [
            self::PAYMENT_STATUS_PENDING_DB => self::PAYMENT_STATUS_PENDING_LABEL,
            self::PAYMENT_STATUS_COMPLETE_DB => self::PAYMENT_STATUS_COMPLETE_LABEL,
            self::PAYMENT_STATUS_FAIL_DB => self::PAYMENT_STATUS_FAIL_LABEL,
        ];

        if($value !== null && isset($status[$value]))
            return $status[$value];

        return $status;
    }

    public function completePayment($note = null, $payByPoint = false, $detail = null)
    {
        if(empty($this->cancelled_at) && $this->payment_status == self::PAYMENT_STATUS_PENDING_DB)
        {
            $this->payment_status = self::PAYMENT_STATUS_COMPLETE_DB;

            $transaction = new OrderTransaction();
            $transaction->order_id = $this->id;

            if($payByPoint == false)
            {
                $transaction->amount = $this->total_price;
                $transaction->point_amount = 0;
            }
            else
            {
                $transaction->amount = 0;
                $transaction->point_amount = $this->total_point_price;
            }

            $transaction->type = self::PAYMENT_STATUS_COMPLETE_DB;
            $transaction->created_at = date('Y-m-d H:i:s');

            if($note !== null)
                $transaction->note = $note;

            if($detail !== null)
                $transaction->detail = $detail;

            $transaction->save();

            foreach($this->orderItems as $orderItem)
            {
                $userCourse = new UserCourse();
                $userCourse->user_id = $this->user_id;
                $userCourse->course_id = $orderItem->course_id;
                $userCourse->order_id = $this->id;
                $userCourse->save();

                $orderItem->course->bought_count ++;
                $orderItem->course->save();
            }

            $pointEarn = round($transaction->amount / Setting::getSettings(Setting::CATEGORY_GENERAL_DB, Setting::EXCHANGE_POINT_RATE), 0, PHP_ROUND_HALF_DOWN);

            $this->user_earn_point = $pointEarn;
            $this->save();

            $this->user->studentInformation->course_count += 1;
            $this->user->studentInformation->total_spent += $transaction->amount;
            $this->user->studentInformation->current_point += $pointEarn;
            $this->user->studentInformation->total_point += $pointEarn;
            $this->user->studentInformation->save();

            $this->addCollaboratorCommission($transaction);

            $this->addTeacherCommission($transaction);

            return true;
        }

        return false;
    }

    protected function addCollaboratorCommission($transaction)
    {
        if(!empty($transaction->amount) && !empty($this->referral) && $this->referral->status == Utility::ACTIVE_DB)
        {
            if(!empty($this->referral->collaboratorInformation) && $this->referral->collaboratorInformation->status == Collaborator::STATUS_ACTIVE_DB)
            {
                $revenue = 0;

                foreach($this->orderItems as $orderItem)
                {
                    if($orderItem->referral_item)
                    {
                        $revenue = $orderItem->price - $this->total_discount_price;
                        break;
                    }
                }

                if($revenue > 0)
                {
                    $collaboratorTransaction = new CollaboratorTransaction();
                    $collaboratorTransaction->collaborator_id = $this->referral_id;
                    $collaboratorTransaction->order_id = $this->id;
                    $collaboratorTransaction->type = CollaboratorTransaction::TYPE_INCOME_DB;
                    $collaboratorTransaction->commission_percent = $this->referral->collaboratorInformation->commission_percent;
                    $collaboratorTransaction->commission_amount = round($revenue * $collaboratorTransaction->commission_percent / 100);
                    $collaboratorTransaction->created_at = date('Y-m-d H:i:s');
                    $collaboratorTransaction->save();

                    $this->referral->collaboratorInformation->total_revenue += $revenue;
                    $this->referral->collaboratorInformation->total_commission += $collaboratorTransaction->commission_amount;
                    $this->referral->collaboratorInformation->current_revenue += $revenue;
                    $this->referral->collaboratorInformation->current_commission += $collaboratorTransaction->commission_amount;
                    $this->referral->collaboratorInformation->save();

                    if(!empty($this->referral->collaboratorInformation->parentCollaborator) && $this->referral->collaboratorInformation->parentCollaborator->status == Collaborator::STATUS_ACTIVE_DB
                        && $this->referral->collaboratorInformation->parentCollaborator->user->status == Utility::ACTIVE_DB && $this->referral->collaboratorInformation->parentCollaborator->rank->code == Setting::COLLABORATOR_MANAGER)
                    {
                        $parentCollaboratorRank = json_decode($this->referral->collaboratorInformation->parentCollaborator->rank->value, true);

                        $collaboratorTransaction = new CollaboratorTransaction();
                        $collaboratorTransaction->collaborator_id = $this->referral->collaboratorInformation->parentCollaborator->user_id;
                        $collaboratorTransaction->order_id = $this->id;
                        $collaboratorTransaction->type = CollaboratorTransaction::TYPE_DOWNLINE_INCOME_DB;
                        $collaboratorTransaction->commission_percent = $parentCollaboratorRank[Collaborator::COMMISSION_DOWNLINE_ATTRIBUTE];
                        $collaboratorTransaction->commission_amount = round($revenue * $collaboratorTransaction->commission_percent / 100);
                        $collaboratorTransaction->created_at = date('Y-m-d H:i:s');
                        $collaboratorTransaction->downline_collaborator_id = $this->referral_id;
                        $collaboratorTransaction->save();
                    }
                }
            }
        }
    }

    protected function addTeacherCommission($transaction)
    {
        if(!empty($transaction->amount))
        {
            foreach($this->orderItems as $orderItem)
            {
                if($orderItem->price > 0)
                {
                    if($orderItem->course->commission_value > 0)
                    {
                        $teacherTransaction = new TeacherTransaction();
                        $teacherTransaction->teacher_id = $orderItem->course->user_id;
                        $teacherTransaction->order_id = $this->id;
                        $teacherTransaction->type = CollaboratorTransaction::TYPE_INCOME_DB;
                        $teacherTransaction->created_at = date('Y-m-d H:i:s');

                        if($orderItem->course->commission_type == Discount::TYPE_FIX_AMOUNT_DB)
                            $teacherTransaction->commission_amount = ($orderItem->course->commission_value > $orderItem->price ? $orderItem->price : $orderItem->course->commission_value);
                        else
                        {
                            $teacherTransaction->commission_percent = $orderItem->course->commission_value;
                            $teacherTransaction->commission_amount = round($orderItem->price * $orderItem->course->commission_value / 100);
                        }

                        $teacherTransaction->save();

                        $orderItem->course->user->teacherInformation->total_commission += $teacherTransaction->commission_amount;
                        $orderItem->course->user->teacherInformation->current_commission += $teacherTransaction->commission_amount;
                        $orderItem->course->user->teacherInformation->save();
                    }
                }
            }
        }
    }

    public function failPayment($note = null, $detail = null)
    {
        if(empty($this->cancelled_at) && $this->payment_status == self::PAYMENT_STATUS_PENDING_DB)
        {
            $this->payment_status = self::PAYMENT_STATUS_FAIL_DB;
            $this->save();

            $transaction = new OrderTransaction();
            $transaction->order_id = $this->id;
            $transaction->amount = $this->total_price;
            $transaction->point_amount = 0;
            $transaction->type = self::PAYMENT_STATUS_FAIL_DB;
            $transaction->created_at = date('Y-m-d H:i:s');

            if($note !== null)
                $transaction->note = $note;

            if($detail !== null)
                $transaction->detail = $detail;

            $transaction->save();
        }
    }

    public function cancelOrder()
    {
        if(empty($this->cancelled_at) && $this->payment_status != self::PAYMENT_STATUS_COMPLETE_DB)
        {
            $this->cancelled_at = date('Y-m-d H:i:s');
            $this->save();

            $this->user->studentInformation->cancelled_order_count += 1;
            $this->user->studentInformation->save();
        }
    }
}