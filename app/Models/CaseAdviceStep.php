<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CaseAdviceStep extends Model
{
    protected $table = 'case_advice_step';

    public $timestamps = false;

    public function caseAdvice()
    {
        return $this->belongsTo('App\Models\CaseAdvice', 'case_id');
    }
}