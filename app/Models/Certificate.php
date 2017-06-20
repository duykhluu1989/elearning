<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Certificate extends Model
{
    protected $table = 'certificate';

    public $timestamps = false;

    public function countCertificateApplies()
    {
        return CertificateApply::where('certificate_id', $this->id)->count('id');
    }

    public function isDeletable()
    {
        if($this->countCertificateApplies() > 0)
            return false;

        return true;
    }
}