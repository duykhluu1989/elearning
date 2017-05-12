<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    const ROLE_ADMINISTRATOR = 'Administrator';

    protected $table = 'role';

    public $timestamps = false;

    public function userRoles()
    {
        return $this->hasMany('App\Models\UserRole', 'role_id');
    }

    public static function initCoreRoles()
    {
        $role = new Role();
        $role->name = self::ROLE_ADMINISTRATOR;
        $role->save();
    }
}