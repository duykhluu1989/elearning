<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Libraries\Widgets\GridView;
use App\Libraries\Helpers\Html;
use App\Libraries\Helpers\Utility;
use App\Models\Certificate;
use App\Models\CertificateApply;

class CertificateController extends Controller
{
    public function adminCertificate(Request $request)
    {
        $dataProvider = Certificate::select('id', 'name', 'status', 'order', 'code', 'price')->orderBy('id', 'desc');

        $inputs = $request->all();

        if(count($inputs) > 0)
        {
            if(!empty($inputs['name']))
                $dataProvider->where('name', 'like', '%' . $inputs['name'] . '%');

            if(!empty($inputs['code']))
                $dataProvider->where('code', 'like', '%' . $inputs['code'] . '%');

            if(isset($inputs['status']) && $inputs['status'] !== '')
                $dataProvider->where('status', $inputs['status']);
        }

        $dataProvider = $dataProvider->paginate(GridView::ROWS_PER_PAGE);

        $columns = [
            [
                'title' => 'Tên',
                'data' => function($row) {
                    echo Html::a($row->name, [
                        'href' => action('Backend\CertificateController@editCertificate', ['id' => $row->id]),
                    ]);
                },
            ],
            [
                'title' => 'Mã',
                'data' => 'code',
            ],
            [
                'title' => 'Lệ Phí',
                'data' => function($row) {
                    echo Utility::formatNumber($row->price) . ' VND';
                },
            ],
            [
                'title' => 'Thứ Tự',
                'data' => 'order',
            ],
            [
                'title' => 'Trạng Thái',
                'data' => function($row) {
                    $status = Utility::getTrueFalse($row->status);
                    if($row->status == Utility::ACTIVE_DB)
                        echo Html::span($status, ['class' => 'label label-success']);
                    else
                        echo Html::span($status, ['class' => 'label label-danger']);
                },
            ],
        ];

        $gridView = new GridView($dataProvider, $columns);
        $gridView->setCheckbox();
        $gridView->setFilters([
            [
                'title' => 'Tên',
                'name' => 'name',
                'type' => 'input',
            ],
            [
                'title' => 'Mã',
                'name' => 'code',
                'type' => 'input',
            ],
            [
                'title' => 'Trạng Thái',
                'name' => 'status',
                'type' => 'select',
                'options' => Utility::getTrueFalse(),
            ],
        ]);
        $gridView->setFilterValues($inputs);

        return view('backend.certificates.admin_certificate', [
            'gridView' => $gridView,
        ]);
    }

    public function createCertificate(Request $request)
    {
        Utility::setBackUrlCookie($request, '/admin/certificate?');

        $certificate = new Certificate();
        $certificate->status = Utility::ACTIVE_DB;
        $certificate->order = 1;

        return $this->saveCertificate($request, $certificate);
    }

    public function editCertificate(Request $request, $id)
    {
        Utility::setBackUrlCookie($request, '/admin/certificate?');

        $certificate = Certificate::find($id);

        if(empty($certificate))
            return view('backend.errors.404');

        return $this->saveCertificate($request, $certificate, false);
    }

    protected function saveCertificate($request, $certificate, $create = true)
    {
        if($request->isMethod('post'))
        {
            $inputs = $request->all();

            if(!empty($inputs['price']))
                $inputs['price'] = implode('', explode('.', $inputs['price']));

            $validator = Validator::make($inputs, [
                'name' => 'required|unique:certificate,name' . ($create == true ? '' : (',' . $certificate->id)),
                'name_en' => 'nullable|unique:certificate,name_en' . ($create == true ? '' : (',' . $certificate->id)),
                'code' => 'required|alpha_num|unique:certificate,code' . ($create == true ? '' : (',' . $certificate->id)),
                'price' => 'nullable|integer|min:1',
                'order' => 'required|integer|min:1',
            ]);

            if($validator->passes())
            {
                $certificate->name = $inputs['name'];
                $certificate->name_en = $inputs['name_en'];
                $certificate->price = $inputs['price'];
                $certificate->status = isset($inputs['status']) ? Utility::ACTIVE_DB : Utility::INACTIVE_DB;
                $certificate->order = $inputs['order'];
                $certificate->code = $inputs['code'];
                $certificate->save();

                return redirect()->action('Backend\CertificateController@editCertificate', ['id' => $certificate->id])->with('messageSuccess', 'Thành Công');
            }
            else
            {
                if($create == true)
                    return redirect()->action('Backend\CertificateController@createCertificate')->withErrors($validator)->withInput();
                else
                    return redirect()->action('Backend\CertificateController@editCertificate', ['id' => $certificate->id])->withErrors($validator)->withInput();
            }
        }

        if($create == true)
        {
            return view('backend.certificates.create_certificate', [
                'certificate' => $certificate,
            ]);
        }
        else
        {
            return view('backend.certificates.edit_certificate', [
                'certificate' => $certificate,
            ]);
        }
    }

    public function deleteCertificate($id)
    {
        $certificate = Certificate::find($id);

        if(empty($certificate) || $certificate->isDeletable() == false)
            return view('backend.errors.404');

        $certificate->delete();

        return redirect(Utility::getBackUrlCookie(action('Backend\CertificateController@adminCertificate')))->with('messageSuccess', 'Thành Công');
    }

    public function controlDeleteCertificate(Request $request)
    {
        $ids = $request->input('ids');

        $certificates = Certificate::whereIn('id', explode(';', $ids))->get();

        foreach($certificates as $certificate)
        {
            if($certificate->isDeletable() == true)
                $certificate->delete();
        }

        if($request->headers->has('referer'))
            return redirect($request->headers->get('referer'))->with('messageSuccess', 'Thành Công');
        else
            return redirect()->action('Backend\CertificateController@adminCertificate')->with('messageSuccess', 'Thành Công');
    }

    public function autoCompleteCertificate(Request $request)
    {
        $term = $request->input('term');

        $builder = Certificate::select('id', 'name')->where('name', 'like', '%' . $term . '%')->limit(Utility::AUTO_COMPLETE_LIMIT);

        $certificates = $builder->get()->toJson();

        return $certificates;
    }

    public function adminCertificateApply(Request $request)
    {
        $dataProvider = CertificateApply::with(['certificate' => function($query) {
            $query->select('id', 'name', 'code');
        }])
            ->select('certificate_apply.id', 'certificate_apply.name', 'certificate_apply.status', 'certificate_apply.certificate_id', 'certificate_apply.phone', 'certificate_apply.created_at')
            ->orderBy('id', 'desc');

        $inputs = $request->all();

        if(count($inputs) > 0)
        {
            if(!empty($inputs['name']))
                $dataProvider->where('certificate_apply.name', 'like', '%' . $inputs['name'] . '%');

            if(!empty($inputs['certificate_name']))
            {
                $dataProvider->join('certificate', 'certificate.id', '=', 'certificate_apply.certificate_id')
                    ->where('certificate.name', 'like', '%' . $inputs['certificate_name'] . '%');
            }

            if(!empty($inputs['certificate_code']))
            {
                $sql = $dataProvider->toSql();
                if(strpos($sql, 'inner join `certificate` on') === false)
                    $dataProvider->join('certificate', 'certificate.id', '=', 'certificate_apply.certificate_id');
                $dataProvider->where('certificate.code', 'like', '%' . $inputs['certificate_code'] . '%');
            }

            if(!empty($inputs['phone']))
                $dataProvider->where('certificate_apply.phone', 'like', '%' . $inputs['phone'] . '%');

            if(isset($inputs['status']) && $inputs['status'] !== '')
                $dataProvider->where('certificate_apply.status', $inputs['status']);
        }

        $dataProvider = $dataProvider->paginate(GridView::ROWS_PER_PAGE);

        $columns = [
            [
                'title' => 'Tên',
                'data' => function($row) {
                    echo Html::a($row->name, [
                        'href' => action('Backend\CertificateController@editCertificateApply', ['id' => $row->id]),
                    ]);
                },
            ],
            [
                'title' => 'Chứng Chỉ',
                'data' => function($row) {
                    echo $row->certificate->name;
                },
            ],
            [
                'title' => 'Mã Chứng Chỉ',
                'data' => function($row) {
                    echo $row->certificate->code;
                },
            ],
            [
                'title' => 'Số Điện Thoại',
                'data' => 'phone',
            ],
            [
                'title' => 'Thời Gian Đăng Kí',
                'data' => 'created_at',
            ],
            [
                'title' => 'Trạng Thái',
                'data' => function($row) {
                    $status = CertificateApply::getCertificateApplyStatus($row->status);
                    if($row->status == Utility::ACTIVE_DB)
                        echo Html::span($status, ['class' => 'label label-success']);
                    else
                        echo Html::span($status, ['class' => 'label label-danger']);
                },
            ],
        ];

        $gridView = new GridView($dataProvider, $columns);
        $gridView->setCheckbox();
        $gridView->setFilters([
            [
                'title' => 'Tên',
                'name' => 'name',
                'type' => 'input',
            ],
            [
                'title' => 'Chứng Chỉ',
                'name' => 'certificate_name',
                'type' => 'input',
            ],
            [
                'title' => 'Mã Chứng Chỉ',
                'name' => 'certificate_code',
                'type' => 'input',
            ],
            [
                'title' => 'Số Điện Thoại',
                'name' => 'phone',
                'type' => 'input',
            ],
            [
                'title' => 'Trạng Thái',
                'name' => 'status',
                'type' => 'select',
                'options' => CertificateApply::getCertificateApplyStatus(),
            ],
        ]);
        $gridView->setFilterValues($inputs);

        return view('backend.certificates.admin_certificate_apply', [
            'gridView' => $gridView,
        ]);
    }

    public function createCertificateApply(Request $request)
    {
        Utility::setBackUrlCookie($request, '/admin/certificateApply?');

        $apply = new CertificateApply();
        $apply->status = Utility::INACTIVE_DB;

        return $this->saveCertificateApply($request, $apply);
    }

    public function editCertificateApply(Request $request, $id)
    {
        Utility::setBackUrlCookie($request, '/admin/certificateApply?');

        $apply = CertificateApply::with(['certificate' => function($query) {
            $query->select('id', 'name');
        }])->find($id);

        if(empty($apply))
            return view('backend.errors.404');

        return $this->saveCertificateApply($request, $apply, false);
    }

    protected function saveCertificateApply($request, $apply, $create = true)
    {
        if($request->isMethod('post'))
        {
            $inputs = $request->all();

            $validator = Validator::make($inputs, [
                'name' => 'required',
                'phone' => 'required|numeric',
                'certificate_name' => 'required',
            ]);

            $validator->after(function($validator) use(&$inputs) {
                $certificate = Certificate::select('id')->where('name', $inputs['certificate_name'])->first();

                if(empty($certificate))
                    $validator->errors()->add('certificate_name', 'Chứng Chỉ Không Tồn Tại');
                else
                    $inputs['certificate_id'] = $certificate->id;
            });

            if($validator->passes())
            {
                $apply->certificate_id = $inputs['certificate_id'];
                $apply->name = $inputs['name'];
                $apply->phone = $inputs['phone'];
                $apply->status = isset($inputs['status']) ? Utility::ACTIVE_DB : Utility::INACTIVE_DB;

                if($create == true)
                    $apply->created_at = date('Y-m-d H:i:s');

                $apply->save();

                return redirect()->action('Backend\CertificateController@editCertificateApply', ['id' => $apply->id])->with('messageSuccess', 'Thành Công');
            }
            else
            {
                if($create == true)
                    return redirect()->action('Backend\CertificateController@createCertificateApply')->withErrors($validator)->withInput();
                else
                    return redirect()->action('Backend\CertificateController@editCertificateApply', ['id' => $apply->id])->withErrors($validator)->withInput();
            }
        }

        if($create == true)
        {
            return view('backend.certificates.create_certificate_apply', [
                'apply' => $apply,
            ]);
        }
        else
        {
            return view('backend.certificates.edit_certificate_apply', [
                'apply' => $apply,
            ]);
        }
    }
}