<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TagCourse extends Model
{
    protected $table = 'tag_course';

    public $timestamps = false;

    public function tag()
    {
        return $this->belongsTo('App\Models\Tag', 'tag_id');
    }

    public function course()
    {
        return $this->belongsTo('App\Models\Course', 'course_id');
    }
}