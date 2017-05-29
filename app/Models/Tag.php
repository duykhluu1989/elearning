<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    protected $table = 'tag';

    public $timestamps = false;

    public function countTagCourses()
    {
        return TagCourse::where('tag_id', $this->id)->count('id');
    }

    public function isDeletable()
    {
        if($this->countTagCourses() > 0)
            return false;

        return true;
    }
}