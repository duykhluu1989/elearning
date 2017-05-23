<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CourseItem extends Model
{
    protected $table = 'course_item';

    public $timestamps = false;

    public function course()
    {
        return $this->belongsTo('App\Models\Course', 'course_id');
    }
}