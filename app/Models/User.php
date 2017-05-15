<?php

namespace App\Models;

use Illuminate\Support\Facades\Hash;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    const STATUS_ACTIVE_DB = 1;
    const STATUS_INACTIVE_DB = 0;
    const STATUS_ACTIVE_LABEL = 'Hợp Lệ';
    const STATUS_INACTIVE_LABEL = 'Trục Xuất';

    const ADMIN_TRUE_LABEL = 'Quản Trị';
    const ADMIN_FALSE_LABEL = 'Học Viên';

    protected $table = 'user';

    public $timestamps = false;

    public function profile()
    {
        return $this->hasOne('App\Models\Profile', 'user_id');
    }

    public function userRoles()
    {
        return $this->hasMany('App\Models\UserRole', 'user_id');
    }

    public static function initCoreUser()
    {
        $user = new User();
        $user->username = 'admin';
        $user->password = Hash::make('123456');
        $user->status = self::STATUS_ACTIVE_DB;
        $user->email = 'admin@gmail.com';
        $user->admin = true;
        $user->created_at = date('Y-m-d H:i:s');
        $user->save();
    }

    public static function getUserStatus($value = null)
    {
        $status = [
            self::STATUS_ACTIVE_DB => self::STATUS_ACTIVE_LABEL,
            self::STATUS_INACTIVE_DB => self::STATUS_INACTIVE_LABEL,
        ];

        if($value !== null && isset($status[$value]))
            return $status[$value];

        return $status;
    }

    public static function getUserAdmin($value = null)
    {
        $admin = [
            self::STATUS_ACTIVE_DB => self::ADMIN_TRUE_LABEL,
            self::STATUS_INACTIVE_DB => self::ADMIN_FALSE_LABEL,
        ];

        if($value !== null && isset($admin[$value]))
            return $admin[$value];

        return $admin;
    }
}
