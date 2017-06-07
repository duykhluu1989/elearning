<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Collaborator extends Model
{
    const COUPON_ATTRIBUTE = 'coupon';
    const COMMISSION_ATTRIBUTE = 'commission';
    const REVENUE_ATTRIBUTE = 'revenue';
    const COMMISSION_DOWNLINE_ATTRIBUTE = 'commission_downline';

    protected $table = 'collaborator';

    public $timestamps = false;
}