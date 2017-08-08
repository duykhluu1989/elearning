<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Libraries\Helpers\Utility;

class Discount extends Model
{
    const TYPE_PERCENTAGE_DB = 1;
    const TYPE_FIX_AMOUNT_DB = 0;
    const TYPE_PERCENTAGE_LABEL = 'Phần Trăm';
    const TYPE_FIX_AMOUNT_LABEL = 'Cố Định';

    protected $table = 'discount';

    public $timestamps = false;

    public function user()
    {
        return $this->belongsTo('App\Models\User', 'user_id');
    }

    public function discountApplies()
    {
        return $this->hasMany('App\Models\DiscountApply', 'discount_id');
    }

    public function collaborator()
    {
        return $this->belongsTo('App\Models\User', 'collaborator_id');
    }

    public static function getDiscountType($value = null)
    {
        $type = [
            self::TYPE_FIX_AMOUNT_DB => self::TYPE_FIX_AMOUNT_LABEL,
            self::TYPE_PERCENTAGE_DB => self::TYPE_PERCENTAGE_LABEL,
        ];

        if($value !== null && isset($type[$value]))
            return $type[$value];

        return $type;
    }

    public function isDeletable()
    {
        if($this->used_count > 0)
            return false;

        return true;
    }

    public function doDelete()
    {
        $this->delete();

        foreach($this->discountApplies as $discountApply)
            $discountApply->delete();
    }

    public static function generateCodeByNumberCharacter($number)
    {
        $characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';

        $charactersLength = strlen($characters);
        $times = 0;
        $maxTimes = 20;

        do
        {
            $randomString = '';

            for($i = 0; $i < $number; $i++)
                $randomString .= $characters[rand(0, $charactersLength - 1)];

            $discount = Discount::where('code', $randomString)->first();

            $times ++;
        }
        while(!empty($discount) && $times < $maxTimes);

        if(empty($discount))
            return $randomString;

        return null;
    }

    public static function calculateDiscountPrice($code, $cart, $user)
    {
        $result = [
            'status' => 'error',
            'message' => trans('theme.discount_invalid'),
            'discount' => '',
            'discountPrice' => 0,
        ];

        $discount = Discount::with('discountApplies')->where('code', $code)->where('status', Utility::ACTIVE_DB)->first();

        if(empty($discount))
            return $result;

        if(!empty($discount->collaborator_id))
        {
            if(request()->hasCookie(Utility::REFERRAL_COOKIE_NAME))
            {
                $referralData = json_decode(request()->cookie(Utility::REFERRAL_COOKIE_NAME), true);

                if(!is_array($referralData))
                    $referralData = array();

                if(isset($referralData['referral']) && isset($referralData['coupon']) && isset($referralData['course']))
                {
                    $referral = User::select('user.id')
                        ->join('collaborator', 'user.id', '=', 'collaborator.user_id')
                        ->where('user.status', Utility::ACTIVE_DB)
                        ->where('collaborator.status', Collaborator::STATUS_ACTIVE_DB)
                        ->where('collaborator.code', $referralData['referral'])
                        ->first();

                    if(!empty($referral) && $referral->id == auth()->user()->id)
                        $referral = null;

                    if(empty($referral) || $referral->id != $discount->collaborator_id)
                        return $result;

                    $discountApplyCoupon = new DiscountApply();
                    $discountApplyCoupon->discount_id = $discount->id;
                    $discountApplyCoupon->apply_id = $referralData['course'];
                    $discountApplyCoupon->target = DiscountApply::TARGET_COURSE_DB;

                    $discount->setRelation('discountApplies', [$discountApplyCoupon]);
                }
                else
                    return $result;
            }
            else
                return $result;
        }

        $courses = Course::with(['promotionPrice' => function($query) {
            $query->select('course_id', 'status', 'price', 'start_time', 'end_time');
        }, 'categoryCourses' => function($query) {
            $query->orderBy('level');
        }])->select('id', 'price')
            ->whereIn('id', $cart->cartItems)
            ->get();

        $totalPrice = 0;

        foreach($courses as $course)
        {
            if($course->validatePromotionPrice())
                $totalPrice += $course->promotionPrice->price;
            else
                $totalPrice += $course->price;
        }

        if(!empty($discount->minimum_order_amount) && $totalPrice < $discount->minimum_order_amount)
            return $result;

        $time = time();
        $startTime = strtotime($discount->start_time);
        $endTime = strtotime($discount->end_time);

        if($time < $startTime || $time > $endTime)
            return $result;

        if(!empty($discount->usage_limit) && $discount->used_count >= $discount->usage_limit)
            return $result;

        if(!empty($discount->usage_unique))
        {
            $userUsedCount = Order::where('user_id', $user->id)->where('discount_id', $discount->id)->count('id');

            if($userUsedCount >= $discount->usage_unique)
                return $result;
        }

        if(!empty($discount->campaign_code))
        {
            $userUsedInCampaign = Order::select('order.id')
                ->join('discount', 'order.discount_id', '=', 'discount.id')
                ->where('order.user_id', $user->id)
                ->where('discount.campaign_code', $discount->campaign_code)
                ->where('discount.code', '<>', $discount->code)
                ->first();

            if(!empty($userUsedInCampaign))
                return $result;
        }

        if(!empty($discount->user_id) && $user->id != $discount->user_id)
            return $result;

        if(count($discount->discountApplies) == 0)
        {
            if($discount->type == self::TYPE_FIX_AMOUNT_DB)
            {
                $discountPrice = $discount->value;

                if($discountPrice > $totalPrice)
                    $discountPrice = $totalPrice;
            }
            else
            {
                $discountPrice = round($totalPrice * $discount->value / 100);

                if(!empty($discount->value_limit) && $discountPrice > $discount->value_limit)
                    $discountPrice = $discount->value_limit;
            }
        }
        else
        {
            $discountPrice = 0;

            foreach($discount->discountApplies as $discountApply)
            {
                foreach($courses as $course)
                {
                    if($course->validatePromotionPrice())
                        $coursePrice = $course->promotionPrice->price;
                    else
                        $coursePrice = $course->price;

                    if($discountApply->target == DiscountApply::TARGET_COURSE_DB)
                    {
                        if($course->id == $discountApply->apply_id)
                        {
                            if($discount->type == self::TYPE_FIX_AMOUNT_DB)
                            {
                                $discountPrice = $discount->value;

                                if($discountPrice > $coursePrice)
                                    $discountPrice = $coursePrice;
                            }
                            else
                            {
                                $discountPrice = round($coursePrice * $discount->value / 100);

                                if(!empty($discount->value_limit) && $discountPrice > $discount->value_limit)
                                    $discountPrice = $discount->value_limit;
                            }

                            break;
                        }
                    }
                    else
                    {
                        $found = false;

                        foreach($course->categoryCourses as $categoryCourse)
                        {
                            if($categoryCourse->caegory_id == $discountApply->apply_id)
                            {
                                if($discount->type == self::TYPE_FIX_AMOUNT_DB)
                                {
                                    $discountPrice = $discount->value;

                                    if($discountPrice > $coursePrice)
                                        $discountPrice = $coursePrice;
                                }
                                else
                                {
                                    $discountPrice = round($coursePrice * $discount->value / 100);

                                    if(!empty($discount->value_limit) && $discountPrice > $discount->value_limit)
                                        $discountPrice = $discount->value_limit;
                                }

                                $found = true;
                                break;
                            }
                        }

                        if($found == true)
                            break;
                    }
                }
            }
        }

        $result['status'] = 'success';
        $result['discount'] = $discount;
        $result['discountPrice'] = $discountPrice;

        return $result;
    }
}