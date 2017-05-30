<?php

namespace App\Http\Controllers\Backend;

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
        $dataProvider = Discount::select('id', 'code', 'status', 'type', 'code')->orderBy('id', 'desc')->paginate(GridView::ROWS_PER_PAGE);

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
                'title' => 'Loại',
                'data' => 'type',
            ],
            [
                'title' => 'Trạng Thái',
                'data' => function($row) {
                    $status = Discount::getDiscountStatus($row->status);
                    if($row->status == Discount::STATUS_ACTIVE_DB)
                        echo Html::span($status, ['class' => 'text-green']);
                    else
                        echo Html::span($status, ['class' => 'text-red']);
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
        $discount->status = Discount::STATUS_ACTIVE_DB;
        $discount->type = Discount::TYPE_FIX_AMOUNT_DB;
        $discount->value = 1;

        if($request->isMethod('post'))
        {
            $inputs = $request->all();

            $validator = Validator::make($inputs, [
                'code' => 'required|alpha_num|unique:discount,code',
                'start_time' => 'nullable|date',
                'end_time' => 'nullable|date',
                'value' => 'required|integer|min:1',
                'value_limit' => 'nullable|integer|min:1',
                'usage_limit' => 'nullable|integer|min:1',
            ]);

            $validator->after(function($validator) use(&$inputs, $discount) {
               if(!empty($inputs['username']))
               {
                   $user = User::select('id')
                       ->where('username', $inputs['username'])
                       ->first();

                   if(!empty($user))
                       $inputs['user_id'] = $user->id;
               }




                if(!isset($inputs['user_id']))
                    $validator->errors()->add('user_name', 'Giảng Viên Không Tồn Tại');

                $category = Category::select('id', 'parent_id')->where('name', $inputs['category_name'])->first();

                if(empty($category))
                    $validator->errors()->add('category_name', 'Chủ Đề Không Tồn Tại');
                else
                {
                    $categoryIds[] = $category->id;

                    while(!empty($category->parentCategory))
                    {
                        $category = $category->parentCategory;

                        $categoryIds[] = $category->id;
                    }

                    $inputs['categoryIds'] = array_reverse($categoryIds);
                }
            });

            if($validator->passes())
            {
                $discount->code = strtoupper($inputs['code']);
                $discount->save();

                return redirect()->action('Backend\DiscountController@editDiscount', ['id' => $discount->id])->with('message', 'Success');
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

        return view('backend.discounts.edit_discount', [
            'discount' => $discount,
        ]);
    }

    public function deleteDiscount($id)
    {

    }

    public function generateDiscountCode(Request $request)
    {
        $number = $request->input('number');

        return Discount::generateCodeByNumberCharacter($number);
    }
}