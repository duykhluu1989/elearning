<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Libraries\Helpers\Utility;

class Profile extends Model
{
    const GENDER_MALE_LABEL = 'Nam';
    const STATUS_FEMALE_LABEL = 'Nữ';

    protected $table = 'profile';

    public $timestamps = false;

    public function user()
    {
        return $this->belongsTo('App\Models\User', 'user_id');
    }

    public static function initCoreProfile()
    {
        $profile = new Profile();
        $profile->user_id = User::where('username', 'admin')->first()->id;;
        $profile->save();
    }

    public static function getProfileGender($value = null)
    {
        $gender = [
            Utility::ACTIVE_DB => self::GENDER_MALE_LABEL,
            Utility::INACTIVE_DB => self::STATUS_FEMALE_LABEL,
        ];

        if($value !== null && isset($gender[$value]))
            return $gender[$value];

        return $gender;
    }
}