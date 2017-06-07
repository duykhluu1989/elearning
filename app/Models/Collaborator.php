<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Collaborator extends Model
{
    const COUPON_ATTRIBUTE = 'coupon';
    const COMMISSION_ATTRIBUTE = 'commission';
    const REVENUE_ATTRIBUTE = 'revenue';
    const RERANK_TIME_ATTRIBUTE = 'rerank_time';
    const COMMISSION_DOWNLINE_ATTRIBUTE = 'commission_downline';
    const COUPON_DOWNLINE_SET_ATTRIBUTE = 'coupon_downline_set';
    const COMMISSION_DOWNLINE_SET_ATTRIBUTE = 'commission_downline_set';

    protected $table = 'collaborator';

    public $timestamps = false;
}