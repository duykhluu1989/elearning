<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserCourse extends Model
{
    protected $table = 'user_course';

    public $timestamps = false;

    public function user()
    {
        return $this->belongsTo('App\Models\User', 'user_id');
    }

    public function course()
    {
        return $this->belongsTo('App\Models\Course', 'course_id');
    }
}