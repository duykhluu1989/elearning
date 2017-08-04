<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Libraries\Helpers\Utility;

class Expert extends Model
{
    const ONLINE_LABEL = 'Online';
    const OFFLINE_LABEL = 'Offline';

    protected $table = 'expert';

    public $timestamps = false;

    public function user()
    {
        return $this->belongsTo('App\Models\User', 'user_id');
    }

    public static function getExpertOnline($value = null)
    {
        $online = [
            Utility::ACTIVE_DB => self::ONLINE_LABEL,
            Utility::INACTIVE_DB => self::OFFLINE_LABEL,
        ];

        if($value !== null && isset($online[$value]))
            return $online[$value];

        return $online;
    }
}