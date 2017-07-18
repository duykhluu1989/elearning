<?php

namespace App\Models;

use Illuminate\Support\Facades\Hash;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Libraries\Helpers\Utility;

class User extends Authenticatable
{
    const STATUS_ACTIVE_LABEL = 'Hợp Lệ';
    const STATUS_INACTIVE_LABEL = 'Trục Xuất';

    const AVATAR_UPLOAD_PATH = '/uploads/users/avatars';

    protected $table = 'user';

    public $timestamps = false;

    protected $casts = [
        'open_id' => 'array',
    ];

    public function profile()
    {
        return $this->hasOne('App\Models\Profile', 'user_id');
    }

    public function userRoles()
    {
        return $this->hasMany('App\Models\UserRole', 'user_id');
    }

    public function collaboratorInformation()
    {
        return $this->hasOne('App\Models\Collaborator', 'user_id');
    }

    public function studentInformation()
    {
        return $this->hasOne('App\Models\Student', 'user_id');
    }

    public static function initCoreUser()
    {
        $user = new User();
        $user->username = 'admin';
        $user->password = Hash::make('123456');
        $user->status = Utility::ACTIVE_DB;
        $user->email = 'admin@caydenthan.vn';
        $user->admin = Utility::ACTIVE_DB;
        $user->created_at = date('Y-m-d H:i:s');
        $user->collaborator = Utility::INACTIVE_DB;
        $user->teacher = Utility::INACTIVE_DB;
        $user->expert = Utility::INACTIVE_DB;
        $user->save();
    }

    public static function getUserStatus($value = null)
    {
        $status = [
            Utility::ACTIVE_DB => self::STATUS_ACTIVE_LABEL,
            Utility::INACTIVE_DB => self::STATUS_INACTIVE_LABEL,
        ];

        if($value !== null && isset($status[$value]))
            return $status[$value];

        return $status;
    }
}
