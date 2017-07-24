<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CollaboratorTransaction extends Model
{
    const TYPE_INCOME_DB = 0;
    const TYPE_INCOME_FROM_CHILD_DB = 1;

    protected $table = 'collaborator_transaction';

    public $timestamps = false;

    public function user()
    {
        return $this->belongsTo('App\Models\User', 'collaborator_id');
    }

    public function order()
    {
        return $this->belongsTo('App\Models\Order', 'order_id');
    }
}