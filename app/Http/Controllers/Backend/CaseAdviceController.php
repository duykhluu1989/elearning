<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Libraries\Widgets\GridView;
use App\Libraries\Helpers\Html;
use App\Libraries\Helpers\Utility;
use App\Models\CaseAdvice;
use App\Models\CaseAdviceStep;

class CaseAdviceController extends Controller
{
    public function adminCaseAdvice(Request $request)
    {
        $dataProvider = CaseAdvice::select('id', 'name', 'type', 'status', 'phone')->orderBy('id', 'desc');

        $inputs = $request->all();

        if(count($inputs) > 0)
        {
            if(!empty($inputs['name']))
                $dataProvider->where('name', 'like', '%' . $inputs['name'] . '%');

            if(isset($inputs['type']) && $inputs['type'] !== '')
                $dataProvider->where('type', $inputs['type']);

            if(isset($inputs['status']) && $inputs['status'] !== '')
                $dataProvider->where('status', $inputs['status']);

            if(!empty($inputs['phone']))
                $dataProvider->where('phone', 'like', '%' . $inputs['phone'] . '%');

            if(!empty($inputs['adviser']))
                $dataProvider->where('adviser', 'like', '%' . $inputs['adviser'] . '%');
        }

        $dataProvider = $dataProvider->paginate(GridView::ROWS_PER_PAGE);

        $columns = [
            [
                'title' => 'Tên',
                'data' => function($row) {
                    echo Html::a($row->name, [
                        'href' => action('Backend\CaseAdviceController@editCaseAdvice', ['id' => $row->id]),
                    ]);
                },
            ],
            [
                'title' => 'Thứ Tự',
                'data' => 'order',
            ],
            [
                'title' => 'Loại',
                'data' => function($row) {
                    echo CaseAdvice::getCaseAdviceType($row->type);
                },
            ],
            [
                'title' => 'Điện Thoại Tư Vấn',
                'data' => 'phone',
            ],
            [
                'title' => 'Người Tư Vấn',
                'data' => 'adviser',
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
                'title' => 'Loại',
                'name' => 'type',
                'type' => 'select',
                'options' => CaseAdvice::getCaseAdviceType(),
            ],
            [
                'title' => 'Trạng Thái',
                'name' => 'status',
                'type' => 'select',
                'options' => Utility::getTrueFalse(),
            ],
            [
                'title' => 'Điện Thoại Tư Vấn',
                'name' => 'phone',
                'type' => 'input',
            ],
            [
                'title' => 'Người Tư Vấn',
                'name' => 'adviser',
                'type' => 'input',
            ],
        ]);
        $gridView->setFilterValues($inputs);

        return view('backend.caseAdvices.admin_case_advice', [
            'gridView' => $gridView,
        ]);
    }

    public function createCaseAdvice(Request $request)
    {
        Utility::setBackUrlCookie($request, '/admin/advice?');

        $case = new CaseAdvice();
        $case->status = Utility::ACTIVE_DB;
        $case->type = CaseAdvice::TYPE_LAW_DB;
        $case->order = 1;

        return $this->saveCaseAdvice($request, $case);
    }

    public function editCaseAdvice(Request $request, $id)
    {
        Utility::setBackUrlCookie($request, '/admin/advice?');

        $case = CaseAdvice::find($id);

        if(empty($case))
            return view('backend.errors.404');

        return $this->saveCaseAdvice($request, $case, false);
    }

    protected function saveCaseAdvice($request, $case, $create = true)
    {
        if($request->isMethod('post'))
        {
            $inputs = $request->all();

            $validator = Validator::make($inputs, [
                'name' => 'required|unique:case_advice,name' . ($create == true ? '' : (',' . $case->id)),
                'name_en' => 'nullable|unique:case_advice,name_en' . ($create == true ? '' : (',' . $case->id)),
                'order' => 'required|integer|min:1',
                'description' => 'required',
                'slug' => 'nullable|unique:case_advice,slug' . ($create == true ? '' : (',' . $case->id)),
                'slug_en' => 'nullable|unique:case_advice,slug_en' . ($create == true ? '' : (',' . $case->id)),
                'phone' => 'nullable|numeric',
            ]);

            if($validator->passes())
            {
                $case->name = $inputs['name'];
                $case->name_en = $inputs['name_en'];
                $case->description = $inputs['description'];
                $case->description_en = $inputs['description_en'];
                $case->type = $inputs['type'];
                $case->status = isset($inputs['status']) ? Utility::ACTIVE_DB : Utility::INACTIVE_DB;
                $case->phone = $inputs['phone'];
                $case->adviser = $inputs['adviser'];
                $case->order = $inputs['order'];

                if(empty($inputs['slug']))
                    $case->slug = str_slug($case->name);
                else
                    $case->slug = str_slug($inputs['slug']);

                if(empty($inputs['slug_en']))
                    $case->slug_en = str_slug($case->name_en);
                else
                    $case->slug_en = str_slug($inputs['slug_en']);

                $case->save();

                return redirect()->action('Backend\CaseAdviceController@editCaseAdvice', ['id' => $case->id])->with('messageSuccess', 'Thành Công');
            }
            else
            {
                if($create == true)
                    return redirect()->action('Backend\CaseAdviceController@createCaseAdvice')->withErrors($validator)->withInput();
                else
                    return redirect()->action('Backend\CaseAdviceController@editCaseAdvice', ['id' => $case->id])->withErrors($validator)->withInput();
            }
        }

        if($create == true)
        {
            return view('backend.caseAdvices.create_case_advice', [
                'case' => $case,
            ]);
        }
        else
        {
            return view('backend.caseAdvices.edit_case_advice', [
                'case' => $case,
            ]);
        }
    }







    public function adminCaseAdviceStep(Request $request, $id)
    {
        Utility::setBackUrlCookie($request, '/admin/advice?');

        $case = CaseAdvice::select('id', 'name', 'step_count')->with(['caseAdviceSteps' => function($query) {
            $query->select('id', 'case_id', 'content', 'content_en', 'type', 'step')->orderBy('step');
        }])->find($id);

        if(empty($case))
            return view('backend.errors.404');

        $dataProvider = $case->caseAdviceSteps;

        $columns = [
            [
                'title' => 'Tổng Số Bước: ' . $case->step_count,
                'data' => function($row) {
                    echo Html::a('Bước ' . $row->step, [
                        'href' => action('Backend\CaseAdviceController@editCaseAdviceStep', ['id' => $row->id]),
                    ]);
                },
            ],
            [
                'title' => 'Nội Dung',
                'data' => 'content',
            ],
            [
                'title' => 'Nội Dung EN',
                'data' => 'content_en',
            ],
            [
                'title' => 'Loại',
                'data' => function($row) {
                },
            ],
        ];

        $gridView = new GridView($dataProvider, $columns);
        $gridView->setCheckbox();
        $gridView->unsetPagination();

        return view('backend.caseAdvices.admin_case_advice_step', [
            'case' => $case,
            'gridView' => $gridView,
        ]);
    }

    public function createCaseAdviceStep(Request $request, $id)
    {
        $case = CaseAdvice::select('id', 'name', 'step_count')->find($id);

        if(empty($case))
            return view('backend.errors.404');

        $caseStep = new CaseAdviceStep();
        $caseStep->case_id = $case->id;
        $caseStep->type = CaseAdviceStep::TYPE_FREE_DB;
        $caseStep->step = $case->step_count + 1;

        $caseStep->caseAdvice()->associate($case);

        return $this->saveCaseAdviceStep($request, $caseStep);
    }

    public function editCaseAdviceStep(Request $request, $id)
    {
        $caseStep = CaseAdviceStep::with(['caseAdvice' => function($query) {
            $query->select('id', 'name', 'step_count');
        }])->find($id);

        if(empty($caseStep))
            return view('backend.errors.404');

        return $this->saveCaseAdviceStep($request, $caseStep, false);
    }

    protected function saveCaseAdviceStep($request, $caseStep, $create = true)
    {
        if($create == true)
        {
            return view('backend.caseAdvices.create_case_advice_step', [
                'caseStep' => $caseStep,
            ]);
        }
        else
        {
            return view('backend.caseAdvices.edit_case_advice_step', [
                'caseStep' => $caseStep,
            ]);
        }
    }
}