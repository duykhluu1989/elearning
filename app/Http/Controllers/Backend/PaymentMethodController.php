<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Libraries\Widgets\GridView;
use App\Libraries\Helpers\Html;
use App\Libraries\Helpers\Utility;
use App\Libraries\Payments\Payment;
use App\Models\PaymentMethod;

class PaymentMethodController extends Controller
{
    public function adminPaymentMethod()
    {
        $dataProvider = PaymentMethod::select('id', 'name', 'status', 'order')->orderBy('id', 'desc')->paginate(GridView::ROWS_PER_PAGE);

        $columns = [
            [
                'title' => 'Tên',
                'data' => function($row) {
                    echo Html::a($row->name, [
                        'href' => action('Backend\PaymentMethodController@editPaymentMethod', ['id' => $row->id]),
                    ]);
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

        return view('backend.paymentMethods.admin_payment_method', [
            'gridView' => $gridView,
        ]);
    }

    public function updatePaymentMethod(Request $request)
    {
        PaymentMethod::initCorePaymentMethods();

        return redirect()->action('Backend\PaymentMethodController@adminPaymentMethod')->with('messageSuccess', 'Thành Công');
    }

    public function editPaymentMethod(Request $request, $id)
    {
        $paymentMethod = PaymentMethod::find($id);

        if(empty($paymentMethod))
            return view('backend.errors.404');

        $payment = Payment::getPayments($paymentMethod->code);

        if($request->isMethod('post'))
        {
            $inputs = $request->all();

            $validator = Validator::make($inputs, [
                'name' => 'required|unique:level,name,' . $paymentMethod->id,
                'name_en' => 'nullable|unique:level,name_en,' . $paymentMethod->id,
                'order' => 'required|integer|min:1',
            ]);

            $payment->validateAndSetData($paymentMethod, $inputs, $validator);

            if($validator->passes())
            {
                $paymentMethod->name = $inputs['name'];
                $paymentMethod->name_en = $inputs['name_en'];
                $paymentMethod->order = $inputs['order'];
                $paymentMethod->status = isset($inputs['status']) ? Utility::ACTIVE_DB : Utility::INACTIVE_DB;
                $paymentMethod->save();

                return redirect()->action('Backend\PaymentMethodController@editPaymentMethod', ['id' => $paymentMethod->id])->with('messageSuccess', 'Thành Công');
            }
            else
                return redirect()->action('Backend\PaymentMethodController@editPaymentMethod', ['id' => $paymentMethod->id])->withErrors($validator)->withInput();
        }

        return view('backend.paymentMethods.edit_payment_method', [
            'paymentMethod' => $paymentMethod,
            'payment' => $payment,
        ]);
    }

    protected function savePaymentMethod()
    {

    }
}