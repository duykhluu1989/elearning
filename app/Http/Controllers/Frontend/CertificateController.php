<?php

namespace App\Http\Controllers\Frontend;

use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Libraries\Helpers\Utility;
use App\Models\Certificate;
use App\Models\CertificateApply;

class CertificateController extends Controller
{
    public function adminCertificate()
    {
        $certificates = Certificate::select('id', 'name', 'name_en', 'price')
            ->where('status', Utility::ACTIVE_DB)
            ->orderBy('order', 'desc')
            ->paginate(Utility::FRONTEND_ROWS_PER_PAGE);

        return view('frontend.certificates.admin_certificate', [
            'certificates' => $certificates,
        ]);
    }

    public function registerCertificate(Request $request)
    {
        $inputs = $request->all();

        $validator = Validator::make($inputs, [
            'name' => 'required|string|max:255',
            'phone' => 'required|numeric',
            'certificate_id' => 'required',
        ]);

        $validator->after(function($validator) use(&$inputs) {
            $certificate = Certificate::select('id')
                ->where('status', Utility::ACTIVE_DB)
                ->find($inputs['certificate_id']);

            if(empty($certificate))
                $validator->errors()->add('name', trans('theme.invalid_certificate'));
        });

        if($validator->passes())
        {
            $certificateApply = new CertificateApply();
            $certificateApply->certificate_id = $inputs['certificate_id'];
            $certificateApply->name = $inputs['name'];
            $certificateApply->phone = $inputs['phone'];
            $certificateApply->status = Utility::INACTIVE_DB;
            $certificateApply->created_at = date('Y-m-d H:i:s');
            $certificateApply->save();

            return 'Success';
        }
        else
            return json_encode($validator->errors()->messages());
    }
}