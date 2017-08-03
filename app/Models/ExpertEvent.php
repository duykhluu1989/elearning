<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ExpertEvent extends Model
{
    protected $table = 'expert_event';

    public $timestamps = false;

    public function user()
    {
        return $this->belongsTo('App\Models\User', 'expert_id');
    }
}