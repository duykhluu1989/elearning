<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    const STATUS_ACTIVE_DB = 1;
    const STATUS_INACTIVE_DB = 0;

    use Notifiable;

    protected $table = 'user';

    public $timestamps = false;
}
