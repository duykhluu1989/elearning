<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TeacherTransaction extends Model
{
    protected $table = 'teacher_transaction';

    public $timestamps = false;

    public function user()
    {
        return $this->belongsTo('App\Models\User', 'collaborator_id');
    }

    public function order()
    {
        return $this->belongsTo('App\Models\Order', 'order_id');
    }

    public function course()
    {
        return $this->belongsTo('App\Models\Course', 'course_id');
    }
}