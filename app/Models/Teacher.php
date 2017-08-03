<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Teacher extends Model
{
    protected $table = 'teacher';

    public $timestamps = false;

    public function user()
    {
        return $this->belongsTo('App\Models\User', 'user_id');
    }
}