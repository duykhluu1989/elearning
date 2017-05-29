<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Libraries\Widgets\GridView;
use App\Libraries\Helpers\Html;
use App\Models\Discount;

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

        return $this->saveDiscount($request, $discount);
    }

    public function editDiscount(Request $request, $id)
    {
        $discount = Discount::find($id);

        if(empty($discount))
            return view('backend.errors.404');

        return $this->saveDiscount($request, $discount, false);
    }

    protected function saveDiscount($request, $discount, $create = true)
    {
        if($request->isMethod('post'))
        {
            $inputs = $request->all();

            $validator = Validator::make($inputs, [
                'code' => 'required|unique:discount,code' . ($create == true ? '' : (',' . $discount->id)),
            ]);

            if($validator->passes())
            {
                $discount->code = strtolower($inputs['code']);
                $discount->save();

                return redirect()->action('Backend\DiscountController@editDiscount', ['id' => $discount->id])->with('message', 'Success');
            }
            else
            {
                if($create == true)
                    return redirect()->action('Backend\DiscountController@createDiscount')->withErrors($validator)->withInput();
                else
                    return redirect()->action('Backend\DiscountController@editDiscount', ['id' => $discount->id])->withErrors($validator)->withInput();
            }
        }

        if($create == true)
        {
            return view('backend.discounts.create_discount', [
                'discount' => $discount,
            ]);
        }
        else
        {
            return view('backend.discounts.edit_discount', [
                'discount' => $discount,
            ]);
        }
    }
}