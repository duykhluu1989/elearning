<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CollaboratorTransaction extends Model
{
    const TYPE_INCOME_DB = 0;
    const TYPE_DOWNLINE_INCOME_DB = 1;
    const TYPE_PAYMENT_DB = 2;
    const TYPE_INCOME_LABEL = 'Hoa Hồng';
    const TYPE_DOWNLINE_INCOME_LABEL = 'Hoa Hồng Từ CTV Cấp Dưới';
    const TYPE_PAYMENT_LABEL = 'Thanh Toán';
    const TYPE_INCOME_LABEL_EN = 'Commission';
    const TYPE_DOWNLINE_INCOME_LABEL_EN = 'Commission from down line';
    const TYPE_PAYMENT_LABEL_EN = 'Payment';

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

    public static function getTransactionType($value = null, $lang = 'vi')
    {
        $type = [
            self::TYPE_INCOME_DB => self::TYPE_INCOME_LABEL,
            self::TYPE_DOWNLINE_INCOME_DB => self::TYPE_DOWNLINE_INCOME_LABEL,
            self::TYPE_PAYMENT_DB => self::TYPE_PAYMENT_LABEL,
        ];

        $typeEn = [
            self::TYPE_INCOME_DB => self::TYPE_INCOME_LABEL_EN,
            self::TYPE_DOWNLINE_INCOME_DB => self::TYPE_DOWNLINE_INCOME_LABEL_EN,
            self::TYPE_PAYMENT_DB => self::TYPE_PAYMENT_LABEL_EN,
        ];

        if($value !== null && isset($type[$value]))
        {
            if($lang == 'en')
                return $typeEn[$value];
            return $type[$value];
        }

        if($lang == 'en')
            return $typeEn;
        return $type;
    }
}