<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CertificateApply extends Model
{
    protected $table = 'certificate_apply';

    public $timestamps = false;

    public function certificate()
    {
        return $this->belongsTo('App\Models\Certificate', 'certificate_id');
    }
}