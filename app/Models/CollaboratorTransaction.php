<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CollaboratorTransaction extends Model
{
    const TYPE_INCOME_DB = 0;
    const TYPE_DOWNLINE_INCOME_DB = 1;
    const TYPE_INCOME_LABEL = 'Hoa Hồng';
    const TYPE_DOWNLINE_INCOME_LABEL = 'Hoa Hồng Từ CTV Cấp Dưới';

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

    public function downlineCollaborator()
    {
        return $this->belongsTo('App\Models\User', 'downline_collaborator_id');
    }

    public static function getTransactionType($value = null)
    {
        $type = [
            self::TYPE_INCOME_DB => self::TYPE_INCOME_LABEL,
            self::TYPE_DOWNLINE_INCOME_DB => self::TYPE_DOWNLINE_INCOME_LABEL,
        ];

        if($value !== null && isset($type[$value]))
            return $type[$value];

        return $type;
    }
}