<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PromotionPrice extends Model
{
    protected $table = 'promotion_price';

    public $timestamps = false;

    public function course()
    {
        return $this->belongsTo('App\Models\Course', 'course_id');
    }
}