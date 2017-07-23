<?php

namespace App\Http\Controllers\Backend;

use App\Libraries\Helpers\Utility;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Libraries\Widgets\GridView;
use App\Libraries\Helpers\Html;
use App\Models\Order;
use Illuminate\Support\Facades\Validator;

class OrderController extends Controller
{
    public function adminOrder()
    {
        $dataProvider = Order::with(['user' => function($query) {
            $query->select('id');
        }, 'user.profile' => function($query) {
            $query->select('user_id', 'name');
        }, 'paymentMethod' => function($query) {
            $query->select('id', 'name');
        }])->select('id', 'user_id', 'number', 'created_at', 'payment_method_id', 'payment_status', 'total_price')
            ->orderBy('id', 'desc')
            ->paginate(GridView::ROWS_PER_PAGE);

        $columns = [
            [
                'title' => 'Mã',
                'data' => function($row) {
                    echo Html::a($row->number, [
                        'href' => action('Backend\OrderController@detailOrder', ['id' => $row->id]),
                    ]);
                },
            ],
            [
                'title' => 'Tên',
                'data' => function($row) {
                    echo $row->user->profile->name;
                },
            ],
            [
                'title' => 'Tổng Tiền',
                'data' => function($row) {
                    echo Utility::formatNumber($row->total_price) . ' VND';
                },
            ],
            [
                'title' => 'Phương Thức Thanh Toán',
                'data' => function($row) {
                    echo $row->paymentMethod->name;
                },
            ],
            [
                'title' => 'Trạng Thái Thanh Toán',
                'data' => function($row) {
                    $status = Order::getOrderPaymentStatus($row->payment_status);
                    if($row->payment_status == Order::PAYMENT_STATUS_COMPLETE_DB)
                        echo Html::span($status, ['class' => 'label label-success']);
                    else if($row->payment_status == Order::PAYMENT_STATUS_PENDING_DB)
                        echo Html::span($status, ['class' => 'label label-danger']);
                    else
                        echo Html::span($status, ['class' => 'label label-warning']);
                },
            ],
            [
                'title' => 'Thời Gian Đặt Đơn Hàng',
                'data' => 'created_at',
            ],
        ];

        $gridView = new GridView($dataProvider, $columns);

        return view('backend.orders.admin_order', [
            'gridView' => $gridView,
        ]);
    }

    public function detailOrder(Request $request, $id)
    {
        Utility::setBackUrlCookie($request, '/admin/order?');

        $order = Order::with(['user' => function($query) {
            $query->select('id');
        }, 'user.profile' => function($query) {
            $query->select('user_id', 'name');
        }, 'paymentMethod' => function($query) {
            $query->select('id', 'name');
        }, 'orderItems.course' => function($query) {
            $query->select('id', 'name');
        }])->find($id);

        if(empty($order))
            return view('backend.errors.404');

        return view('backend.orders.detail_order', [
            'order' => $order,
        ]);
    }

    public function submitPaymentOrder(Request $request, $id)
    {
        $order = Order::find($id);

        if(empty($order))
            return view('backend.errors.404');

        $inputs = $request->all();

        $validator = Validator::make($inputs, [

        ]);

        if($validator->passes())
        {

        }
        else
        {

        }
    }
}