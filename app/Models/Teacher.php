<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Libraries\Helpers\Utility;

class Teacher extends Model
{
    const ORGANIZATION_LABEL = 'Tổ chức';
    const PERSONAL_LABEL = 'Cá nhân';

    protected $table = 'teacher';

    public $timestamps = false;

    public function user()
    {
        return $this->belongsTo('App\Models\User', 'user_id');
    }

    public static function getTeacherOrganization($value = null)
    {
        $organization = [
            Utility::ACTIVE_DB => self::ORGANIZATION_LABEL,
            Utility::INACTIVE_DB => self::PERSONAL_LABEL,
        ];

        if($value !== null && isset($organization[$value]))
            return $organization[$value];

        return $organization;
    }
}