<?php

namespace App\Models;

use Illuminate\Support\Facades\Hash;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    const STATUS_ACTIVE_DB = 1;
    const STATUS_INACTIVE_DB = 0;

    protected $table = 'user';

    public $timestamps = false;

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
}
