<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Level extends Model
{
    protected $table = 'level';

    public $timestamps = false;

    public function countCourses()
    {
        return Course::where('level_id', $this->id)->count('id');
    }

    public function isDeletable()
    {
        if($this->countCourses() > 0)
            return false;

        return true;
    }
}