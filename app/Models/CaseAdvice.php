<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CaseAdvice extends Model
{
    const TYPE_LAW_DB = 0;
    const TYPE_ECONOMY_DB = 1;
    const TYPE_LAW_LABEL = 'Pháp Luật';
    const TYPE_ECONOMY_LABEL = 'Kinh Tế';

    protected $table = 'case_advice';

    public $timestamps = false;

    public function caseAdviceSteps()
    {
        return $this->hasMany('App\Models\CaseAdviceStep', 'case_id');
    }

    public static function getCaseAdviceType($value = null)
    {
        $type = [
            self::TYPE_LAW_DB => self::TYPE_LAW_LABEL,
            self::TYPE_ECONOMY_DB => self::TYPE_ECONOMY_LABEL,
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