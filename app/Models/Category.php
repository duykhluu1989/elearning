<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $table = 'category';

    public $timestamps = false;

    public function parentCategory()
    {
        return $this->belongsTo('App\Models\Category', 'parent_id');
    }

    public function countCategoryCourses()
    {
        return CategoryCourse::where('category_id', $this->id)->count('id');
    }

    public function isDeletable()
    {
        if($this->countCategoryCourses() > 0)
            return false;

        return true;
    }
}