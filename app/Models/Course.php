<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    protected $table = 'course';

    public $timestamps = false;

    public function user()
    {
        return $this->belongsTo('App\Models\User', 'user_id');
    }
}