<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Discount extends Model
{
    const STATUS_ACTIVE_DB = 1;
    const STATUS_INACTIVE_DB = 0;
    const STATUS_ACTIVE_LABEL = 'Kích Hoạt';
    const STATUS_INACTIVE_LABEL = 'Vô Hiệu';

    protected $table = 'discount';

    public $timestamps = false;

    public static function getDiscountStatus($value = null)
    {
        $status = [
            self::STATUS_ACTIVE_DB => self::STATUS_ACTIVE_LABEL,
            self::STATUS_INACTIVE_DB => self::STATUS_INACTIVE_LABEL,
        ];

        if($value !== null && isset($status[$value]))
            return $status[$value];

        return $status;
    }

    public function isDeletable()
    {
        return false;
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
}