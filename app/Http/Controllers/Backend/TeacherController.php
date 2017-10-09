<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Support\Facades\DB;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Libraries\Helpers\Utility;
use App\Libraries\Widgets\GridView;
use App\Models\User;
use App\Models\Collaborator;
use App\Models\CollaboratorTransaction;
use App\Models\TeacherTransaction;

class TeacherController extends Controller
{
    public function editTeacher(Request $request, $id)
    {
        Utility::setBackUrlCookie($request, '/admin/userTeacher?');

        $teacher = User::select('id', 'username')
            ->with('teacherInformation')
            ->find($id);

        if(empty($teacher) || empty($teacher->teacherInformation))
            return view('backend.errors.404');

        if($request->isMethod('post'))
        {
            $inputs = $request->all();

            $teacher->teacherInformation->status = isset($inputs['status']) ? Collaborator::STATUS_ACTIVE_DB : Collaborator::STATUS_INACTIVE_DB;
            $teacher->teacherInformation->organization = isset($inputs['organization']) ? Utility::ACTIVE_DB : Utility::INACTIVE_DB;
            $teacher->teacherInformation->save();

            return redirect()->action('Backend\TeacherController@editTeacher', ['id' => $teacher->id])->with('messageSuccess', 'Thành Công');
        }

        return view('backend.teachers.edit_teacher', ['teacher' => $teacher]);
    }

    public function adminTeacherTransaction(Request $request, $id)
    {
        Utility::setBackUrlCookie($request, '/admin/userTeacher?');

        $teacher = User::select('id', 'username')
            ->with(['teacherInformation' => function($query) {
                $query->select('user_id', 'current_commission');
            }])->find($id);

        if(empty($teacher) || empty($teacher->teacherInformation))
            return view('backend.errors.404');

        $dataProvider = TeacherTransaction::with(['order' => function($query) {
            $query->select('id', 'number');
        }, 'course' => function($query) {
            $query->select('id', 'name');
        }])->select('teacher_transaction.teacher_id', 'teacher_transaction.order_id', 'teacher_transaction.course_id', 'teacher_transaction.type', 'teacher_transaction.commission_percent', 'teacher_transaction.commission_amount', 'teacher_transaction.created_at', 'teacher_transaction.note')
            ->where('teacher_transaction.teacher_id', $id)
            ->orderBy('teacher_transaction.id', 'desc');

        $inputs = $request->all();

        if(count($inputs) > 0)
        {
            if(!empty($inputs['order_number']))
            {
                $dataProvider->join('order', 'teacher_transaction.order_id', '=', 'order.id')
                    ->where('order.number', 'like', '%' . $inputs['order_number'] . '%');
            }

            if(!empty($inputs['course_name']))
            {
                $dataProvider->join('course', 'teacher_transaction.course_id', '=', 'course.id')
                    ->where('course.name', 'like', '%' . $inputs['course_name'] . '%');
            }

            if(isset($inputs['type']) && $inputs['type'] !== '')
                $dataProvider->where('teacher_transaction.type', $inputs['type']);

            if(!empty($inputs['note']))
                $dataProvider->where('teacher_transaction.note', 'like', '%' . $inputs['note'] . '%');
        }

        $dataProvider = $dataProvider->paginate(GridView::ROWS_PER_PAGE);

        $columns = [
            [
                'title' => 'Thời Gian',
                'data' => 'created_at',
            ],
            [
                'title' => 'Đơn Hàng',
                'data' => function($row) {
                    if(!empty($row->order))
                        echo $row->order->number;
                },
            ],
            [
                'title' => 'Khóa Học',
                'data' => function($row) {
                    if(!empty($row->course))
                        echo $row->course->name;
                },
            ],
            [
                'title' => 'Loại',
                'data' => function($row) {
                    echo CollaboratorTransaction::getTransactionType($row->type);
                },
            ],
            [
                'title' => 'Tỉ Lệ',
                'data' => function($row) {
                    if(!empty($row->commission_percent))
                        echo $row->commission_percent . ' %';
                },
            ],
            [
                'title' => 'Tiền',
                'data' => function($row) {
                    echo Utility::formatNumber($row->commission_amount) . ' VND';
                },
            ],
            [
                'title' => 'Ghi Chú',
                'data' => 'note',
            ],
        ];

        $gridView = new GridView($dataProvider, $columns);
        $gridView->setFilters([
            [
                'title' => 'Đơn Hàng',
                'name' => 'order_number',
                'type' => 'input',
            ],
            [
                'title' => 'Khóa Học',
                'name' => 'course_name',
                'type' => 'input',
            ],
            [
                'title' => 'Loại',
                'name' => 'type',
                'type' => 'select',
                'options' => CollaboratorTransaction::getTransactionType(),
            ],
            [
                'title' => 'Ghi Chú',
                'name' => 'note',
                'type' => 'input',
            ],
        ]);
        $gridView->setFilterValues($inputs);

        return view('backend.teachers.admin_teacher_transaction', [
            'teacher' => $teacher,
            'gridView' => $gridView,
        ]);
    }

    public function paymentTeacher(Request $request, $id)
    {
        $teacher = User::select('id')
            ->with(['teacherInformation' => function($query) {
                $query->select('id', 'user_id', 'current_commission');
            }])->find($id);

        if(empty($teacher) || empty($teacher->teacherInformation))
            return '';

        $inputs = $request->all();

        $inputs['amount'] = str_replace('.', '', $inputs['amount']);

        $validator = Validator::make($inputs, [
            'amount' => 'required|integer|min:1|max:' . $teacher->teacherInformation->current_commission,
            'note' => 'nullable|string|max:255',
        ]);

        if($validator->passes())
        {
            try
            {
                DB::beginTransaction();

                $teacher->teacherInformation->current_commission -= $inputs['amount'];
                $teacher->teacherInformation->save();

                $transaction = new TeacherTransaction();
                $transaction->teacher_id = $teacher->id;
                $transaction->type = CollaboratorTransaction::TYPE_PAYMENT_DB;
                $transaction->commission_amount = $inputs['amount'];
                $transaction->created_at = date('Y-m-d H:i:s');
                $transaction->note = $inputs['note'];
                $transaction->save();

                DB::commit();

                return 'Success';
            }
            catch(\Exception $e)
            {
                DB::rollBack();

                return view('backend.teachers.partials.payment_teacher_form', [
                    'teacher' => $teacher,
                ])->withErrors(['amount' => [$e->getMessage()]]);
            }
        }
        else
            return view('backend.teachers.partials.payment_teacher_form', [
                'teacher' => $teacher,
            ])->withErrors($validator);
    }
}