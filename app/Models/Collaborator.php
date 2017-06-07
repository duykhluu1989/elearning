<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Collaborator extends Model
{
    const DISCOUNT_ATTRIBUTE = 'discount';
    const COMMISSION_ATTRIBUTE = 'commission';
    const REVENUE_ATTRIBUTE = 'revenue';
    const RERANK_TIME_ATTRIBUTE = 'rerank_time';
    const COMMISSION_DOWNLINE_ATTRIBUTE = 'commission_downline';
    const DISCOUNT_DOWNLINE_SET_ATTRIBUTE = 'discount_downline_set';
    const COMMISSION_DOWNLINE_SET_ATTRIBUTE = 'commission_downline_set';

    protected $table = 'collaborator';

    public $timestamps = false;
}