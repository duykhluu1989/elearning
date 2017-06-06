<?php

namespace App\Http\Controllers\Backend;

use App\Libraries\Helpers\Utility;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Libraries\Widgets\GridView;
use App\Libraries\Helpers\Html;
use App\Models\Discount;
use App\Models\User;

class DiscountController extends Controller
{
    public function adminDiscount()
    {
        $dataProvider = Discount::select('id', 'code', 'start_time', 'end_time', 'status', 'type', 'value', 'value_limit', 'code', 'used_count', 'campaign_code', 'minimum_order_amount', 'usage_limit')
            ->orderBy('id', 'desc')->paginate(GridView::ROWS_PER_PAGE);

        $columns = [
            [
                'title' => 'Mã',
                'data' => function($row) {
                    echo Html::a($row->code, [
                        'href' => action('Backend\DiscountController@editDiscount', ['id' => $row->id]),
                    ]);
                },
            ],
            [
                'title' => 'Giá Trị Đơn Hàng Tối Thiểu',
                'data' => function($row) {
                    echo Utility::formatNumber($row->minimum_order_amount) . ' VND';
                },
            ],
            [
                'title' => 'Thời Gian Bắt Đầu',
                'data' => 'start_time',
            ],
            [
                'title' => 'Thời Gian Kết Thúc',
                'data' => 'end_time',
            ],
            [
                'title' => 'Loại Giảm Giá',
                'data' => function($row) {
                    echo Discount::getDiscountType($row->type);
                },
            ],
            [
                'title' => 'Giá Trị Giảm Giá',
                'data' => function($row) {
                    if($row->type == Discount::TYPE_FIX_AMOUNT_DB)
                        echo Utility::formatNumber($row->value) . ' VND';
                    else
                    {
                        $value = $row->value . ' %';
                        if(!empty($row->value_limit))
                            $value .= ' (Tối Đa: ' . Utility::formatNumber($row->value_limit) . ' VND)';
                        echo $value;
                    }
                },
            ],
            [
                'title' => 'Mã Chương Trình',
                'data' => 'campaign_code',
            ],
            [
                'title' => 'Số Lần Sử Dụng Tổng',
                'data' => function($row) {
                    if(empty($row->usage_limit))
                        echo 'Không Giới Hạn';
                    else
                        echo Utility::formatNumber($row->usage_limit);
                },
            ],
            [
                'title' => 'Đã Sử Dụng',
                'data' => function($row) {
                    echo Utility::formatNumber($row->used_count);
                },
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

        return view('backend.discounts.admin_discount', [
            'gridView' => $gridView,
        ]);
    }

    public function createDiscount(Request $request)
    {
        $discount = new Discount();
        $discount->status = Utility::ACTIVE_DB;
        $discount->type = Discount::TYPE_FIX_AMOUNT_DB;
        $discount->value = 1;

        if($request->isMethod('post'))
        {
            $inputs = $request->all();

            $inputs['value'] = implode('', explode('.', $inputs['value']));

            if(!empty($inputs['minimum_order_amount']))
                $inputs['minimum_order_amount'] = implode('', explode('.', $inputs['minimum_order_amount']));

            if(!empty($inputs['value_limit']))
                $inputs['value_limit'] = implode('', explode('.', $inputs['value_limit']));

            $validator = Validator::make($inputs, [
                'code' => 'required_without_all:create_multi_character_number,create_multi_number|nullable|alpha_num|unique:discount,code',
                'create_multi_character_number' => 'required_without:code|nullable|integer|min:1',
                'create_multi_number' => 'required_without:code|nullable|integer|min:1',
                'minimum_order_amount' => 'nullable|integer|min:1',
                'start_time' => 'nullable|date',
                'end_time' => 'nullable|date',
                'value' => 'required|integer|min:1',
                'value_limit' => 'nullable|integer|min:1',
                'usage_limit' => 'nullable|integer|min:1',
                'usage_unique' => 'nullable|integer|min:1',
                'campaign_code' => 'nullable|alpha_num|unique:discount,campaign_code',
            ]);

            $validator->after(function($validator) use(&$inputs, $discount) {
                if(!empty($inputs['username']))
                {
                    $user = User::select('id')
                        ->where('username', $inputs['username'])
                        ->first();

                    if(!empty($user))
                        $inputs['user_id'] = $user->id;
                    else
                        $validator->errors()->add('username', 'Thành Viên Không Tồn Tại');
                }

                if($inputs['type'] == Discount::TYPE_PERCENTAGE_DB && $inputs['value'] > 99)
                    $validator->errors()->add('value', 'Phần trăm giảm giá không được lớn hơn 99');
            });

            if($validator->passes())
            {
                if(!empty($inputs['user_id']))
                    $discount->user_id = $inputs['user_id'];

                $discount->minimum_order_amount = $inputs['minimum_order_amount'];
                $discount->status = isset($inputs['status']) ? Utility::ACTIVE_DB : Utility::INACTIVE_DB;
                $discount->start_time = $inputs['start_time'];
                $discount->end_time = $inputs['end_time'];
                $discount->type = $inputs['type'];
                $discount->value = $inputs['value'];
                $discount->value_limit = $inputs['value_limit'];
                $discount->usage_limit = $inputs['usage_limit'];
                $discount->usage_unique = $inputs['usage_unique'];
                $discount->description = $inputs['description'];
                $discount->campaign_code = strtoupper($inputs['campaign_code']);
                $discount->created_at = date('Y-m-d H:i:s');

                if(empty($inputs['create_multi_character_number']) && empty($inputs['create_multi_number']))
                {
                    $discount->code = strtoupper($inputs['code']);
                    $discount->save();

                    return redirect()->action('Backend\DiscountController@editDiscount', ['id' => $discount->id])->with('messageSuccess', 'Thành Công');
                }
                else
                {
                    for($i = 1;$i <= $inputs['create_multi_number'];$i ++)
                    {
                        $newDiscount = clone $discount;
                        $newDiscount->code = Discount::generateCodeByNumberCharacter($inputs['create_multi_character_number']);
                        if(!empty($newDiscount->code))
                            $newDiscount->save();
                    }

                    return redirect()->action('Backend\DiscountController@adminDiscount')->with('messageSuccess', 'Thành Công');
                }
            }
            else
                return redirect()->action('Backend\DiscountController@createDiscount')->withErrors($validator)->withInput();
        }

        return view('backend.discounts.create_discount', [
            'discount' => $discount,
        ]);
    }

    public function editDiscount(Request $request, $id)
    {
        $discount = Discount::find($id);

        if(empty($discount))
            return view('backend.errors.404');

        if($request->isMethod('post'))
        {
            $inputs = $request->all();

            $validator = Validator::make($inputs, [
                'start_time' => 'nullable|date',
                'end_time' => 'nullable|date',
            ]);

            if($validator->passes())
            {
                $discount->status = isset($inputs['status']) ? Utility::ACTIVE_DB : Utility::INACTIVE_DB;
                $discount->start_time = $inputs['start_time'];
                $discount->end_time = $inputs['end_time'];
                $discount->save();

                return redirect()->action('Backend\DiscountController@editDiscount', ['id' => $discount->id])->with('messageSuccess', 'Thành Công');
            }
            else
                return redirect()->action('Backend\DiscountController@createDiscount')->withErrors($validator)->withInput();
        }

        return view('backend.discounts.edit_discount', [
            'discount' => $discount,
        ]);
    }

    public function deleteDiscount($id)
    {
        $discount = Discount::find($id);

        if(empty($discount) || $discount->isDeletable() == false)
            return view('backend.errors.404');

        $discount->delete();

        return redirect()->action('Backend\DiscountController@adminDiscount')->with('messageSuccess', 'Thành Công');
    }

    public function generateDiscountCode(Request $request)
    {
        $number = $request->input('number');

        return Discount::generateCodeByNumberCharacter($number);
    }
}