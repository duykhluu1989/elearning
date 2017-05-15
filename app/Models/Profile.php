<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Profile extends Model
{
    const GENDER_MALE_DB = 0;
    const STATUS_FEMALE_DB = 1;
    const GENDER_MALE_LABEL = 'Nam';
    const STATUS_FEMALE_LABEL = 'Nữ';

    protected $table = 'profile';

    public $timestamps = false;

    public function user()
    {
        return $this->belongsTo('App\Models\User', 'user_id');
    }

    public static function getProfileGender($value = null)
    {
        $gender = [
            self::GENDER_MALE_DB => self::GENDER_MALE_LABEL,
            self::STATUS_FEMALE_DB => self::STATUS_FEMALE_LABEL,
        ];

        if($value !== null && isset($gender[$value]))
            return $gender[$value];

        return $gender;
    }
}