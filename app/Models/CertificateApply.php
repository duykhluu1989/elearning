<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Libraries\Helpers\Utility;

class CertificateApply extends Model
{
    const STATUS_INACTIVE_LABEL = 'Chờ Thi';
    const STATUS_ACTIVE_LABEL = 'Đã Thi';

    protected $table = 'certificate_apply';

    public $timestamps = false;

    public function certificate()
    {
        return $this->belongsTo('App\Models\Certificate', 'certificate_id');
    }

    public static function getCertificateApplyStatus($value = null)
    {
        $status = [
            Utility::ACTIVE_DB => self::STATUS_ACTIVE_LABEL,
            Utility::INACTIVE_DB => self::STATUS_INACTIVE_LABEL,
        ];

        if($value !== null && isset($status[$value]))
            return $status[$value];

        return $status;
    }
}