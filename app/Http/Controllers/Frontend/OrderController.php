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

            $cart = self::getCart();
            $cart->addCartItem($inputs['course_id']);
            $cart->save();

            self::setCookieCartToken($cart->token);

            return view('frontend.orders.cart', [
                'cart' => self::generateFullCart($cart),
            ]);
        }
        else
            return '';
    }

    protected static function getCart()
    {
        $cart = null;

        $token = self::getCookieCartToken();

        if(!empty($token))
            $cart = Cart::find($token);

        if(empty($cart))
            return new Cart();
        else
            return $cart;
    }

    protected static function generateFullCart($cart)
    {
        $fullCart = [
            'countItem' => 0,
            'totalPrice' => 0,
            'cartItems' => array(),
        ];

        if(!empty($cart->cartItems))
        {
            $courses = Course::with(['promotionPrice' => function($query) {
                $query->select('course_id', 'status', 'price', 'start_time', 'end_time');
            }])->select('id', 'name', 'name_en', 'price', 'image', 'slug', 'slug_en')
                ->whereIn('id', $cart->cartItems)
                ->get();

            $fullCart['countItem'] = count($courses);
            $fullCart['cartItems'] = $courses;
        }

        return $fullCart;
    }

    protected static function getCookieCartToken()
    {
        return request()->cookie(Cart::CART_TOKEN_COOKIE_NAME);
    }

    protected static function setCookieCartToken($token)
    {
        Cookie::queue(Cookie::make(Cart::CART_TOKEN_COOKIE_NAME, $token, Utility::SECOND_ONE_HOUR / 60));
    }

    public static function getFullCart()
    {
        $cart = self::getCart();

        return self::generateFullCart($cart);
    }
}