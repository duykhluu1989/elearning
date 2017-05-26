<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Libraries\Widgets\GridView;
use App\Libraries\Helpers\Html;
use App\Models\PaymentMethod;

class PaymentMethodController extends Controller
{
    public function adminPaymentMethod()
    {
        $dataProvider = PaymentMethod::select('id', 'name', 'status', 'type', 'order', 'code')->orderBy('id', 'desc')->paginate(GridView::ROWS_PER_PAGE);

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
                'title' => 'Mã',
                'data' => 'code',
            ],
            [
                'title' => 'Thứ Tự',
                'data' => 'order',
            ],
            [
                'title' => 'Loại',
                'data' => 'type',
            ],
            [
                'title' => 'Trạng Thái',
                'data' => function($row) {
                    $status = PaymentMethod::getPaymentMethodStatus($row->status);
                    if($row->status == PaymentMethod::STATUS_ACTIVE_DB)
                        echo Html::span($status, ['class' => 'text-green']);
                    else
                        echo Html::span($status, ['class' => 'text-red']);
                },
            ],
        ];

        $gridView = new GridView($dataProvider, $columns);

        return view('backend.paymentMethods.admin_payment_method', [
            'gridView' => $gridView,
        ]);
    }

    public function createPaymentMethod(Request $request)
    {

    }

    public function editPaymentMethod(Request $request, $id)
    {

    }

    protected function savePaymentMethod()
    {

    }
}