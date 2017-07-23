<?php

namespace App\Http\Controllers\Backend;

use App\Models\UserCourse;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Libraries\Widgets\GridView;
use App\Libraries\Helpers\Html;
use App\Libraries\Helpers\Utility;
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
                    else
                        echo Html::span($status, ['class' => 'label label-danger']);
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
        $order = Order::with('orderItems')->where('payment_status', Order::PAYMENT_STATUS_PENDING_DB)
            ->whereNull('cancelled_at')
            ->find($id);

        if(empty($order))
            return '';

        $inputs = $request->all();

        $inputs['amount'] = implode('', explode('.', $inputs['amount']));

        $validator = Validator::make($inputs, [
            'amount' => 'required|integer|min:1',
        ]);

        $validator->after(function($validator) use($order) {
            $courseIds = array();

            foreach($order->orderItems as $orderItem)
                $courseIds[] = $orderItem->course_id;

            $userCourses = UserCourse::select('course_id')->where('user_id', $order->user_id)->whereIn('course_id', $courseIds)->get();

            if(count($userCourses) > 0)
                $validator->errors()->add('amount', 'Đơn hàng này có khóa học đã mua rồi');
        });

        if($validator->passes())
        {
            try
            {
                DB::beginTransaction();

                $order->completePayment();

                DB::commit();

                return 'Success';
            }
            catch(\Exception $e)
            {
                DB::rollBack();

                return view('backend.orders.partials.submit_payment_order_form', [
                    'order' => $order,
                ])->withErrors(['amount' => [$e->getMessage()]]);
            }
        }
        else
            return view('backend.orders.partials.submit_payment_order_form', [
                'order' => $order,
            ])->withErrors($validator);
    }
}