<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CaseAdviceStep extends Model
{
    const TYPE_FREE_DB = 0;
    const TYPE_CHARGE_DB = 1;
    const TYPE_FREE_LABEL = 'Miễn Phí';
    const TYPE_CHARGE_LABEL = 'Tính Phí';

    protected $table = 'case_advice_step';

    public $timestamps = false;

    public function caseAdvice()
    {
        return $this->belongsTo('App\Models\CaseAdvice', 'case_id');
    }

    public static function getCaseAdviceStepType($value = null)
    {
        $type = [
            self::TYPE_FREE_DB => self::TYPE_FREE_LABEL,
            self::TYPE_CHARGE_DB => self::TYPE_CHARGE_LABEL,
        ];

        if($value !== null && isset($type[$value]))
            return $type[$value];

        return $type;
    }

    public function isDeletable()
    {
        return false;
    }
}