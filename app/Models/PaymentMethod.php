<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaymentMethod extends Model
{
    const PAYMENT_TYPE_COD_DB = 0;

    protected $table = 'payment_method';

    public $timestamps = false;
}