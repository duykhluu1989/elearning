<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CategoryCourse extends Model
{
    protected $table = 'category_course';

    public $timestamps = false;

    public function course()
    {
        return $this->belongsTo('App\Models\Course', 'course_id');
    }

    public function category()
    {
        return $this->belongsTo('App\Models\Category', 'category_id');
    }
}