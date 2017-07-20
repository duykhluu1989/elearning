<?php

namespace App\Http\Controllers\Frontend;

use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Libraries\Helpers\Utility;
use App\Models\Course;
use App\RedisModels\Cart;

class OrderController extends Controller
{
    public function addCartItem(Request $request)
    {
        $inputs = $request->all();

        $validator = Validator::make($inputs, [
            'course_id' => 'required',
        ]);

        if($validator->passes())
        {
            $course = Course::select('id')
                ->where('status', Course::STATUS_PUBLISH_DB)
                ->where('category_status', Utility::ACTIVE_DB)
                ->where('id', $inputs['course_id'])
                ->first();

            if(empty($course))
                return '';

            $cart = $this->getCart();
            $cart->addCartItem($inputs['course_id']);
            $cart->save();

            $this->setCookieCartToken($cart->token);

            return $cart;
        }
        else
            return '';
    }

    protected function getCart()
    {
        $cart = null;

        $token = $this->getCookieCartToken();

        if(!empty($token))
            $cart = Cart::find($token);

        if(empty($cart))
            return new Cart();
        else
            return $cart;
    }

    protected function getCookieCartToken()
    {
        return request()->cookie(Cart::CART_TOKEN_COOKIE_NAME);
    }

    protected function setCookieCartToken($token)
    {
        Cookie::queue(Cookie::make(Cart::CART_TOKEN_COOKIE_NAME, $token, Utility::SECOND_ONE_HOUR));
    }
}