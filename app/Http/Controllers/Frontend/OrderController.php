<?php

namespace App\Http\Controllers\Frontend;

use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Libraries\Helpers\Utility;
use App\Models\Course;
use App\Models\Category;
use App\Models\PaymentMethod;
use App\RedisModels\Cart;
use App\Libraries\Payments\Payment;

class OrderController extends Controller
{
    protected static $fullCart;

    public function editCart()
    {
        $category = Category::select('id', 'slug', 'slug_en')
            ->where('status', Utility::ACTIVE_DB)
            ->where('parent_status', Utility::ACTIVE_DB)
            ->whereNull('parent_id')
            ->orderBy('order', 'desc')
            ->first();

        $cart = self::getFullCart();

        return view('frontend.orders.edit_cart', [
            'cart' => $cart,
            'category' => $category,
        ]);
    }

    public function addCartItem(Request $request)
    {
        if($request->ajax() == false)
            return view('frontend.errors.404');

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

    public function deleteCartItem(Request $request)
    {
        if($request->ajax() == false)
            return view('frontend.errors.404');

        $inputs = $request->all();

        $validator = Validator::make($inputs, [
            'course_id' => 'required',
        ]);

        if($validator->passes())
        {
            $cart = self::getCart();
            $cart->deleteCartItem($inputs['course_id']);

            if(empty($cart->cartItems))
            {
                $cart->delete();

                self::deleteCookieCartToken();

                return 'Empty';
            }
            else
            {
                $cart->save();

                self::setCookieCartToken($cart->token);

                return 'Success';
            }
        }
        else
            return '';
    }

    public function placeOrder(Request $request)
    {
        $cart = self::getFullCart();

        $paymentMethods = PaymentMethod::select('id', 'name', 'name_en', 'type', 'detail', 'code')
            ->where('status', Utility::ACTIVE_DB)
            ->orderBy('order', 'desc')
            ->get();

        if($request->isMethod('post'))
        {
            $inputs = $request->all();

            $validator = Validator::make($inputs, [
                'payment_method' => 'required',
            ]);

            $validator->after(function($validator) use(&$inputs, $paymentMethods, $cart) {
                if($cart['countItem'] == 0)
                    $validator->errors()->add('cart', trans('theme.empty_cart'));

                $validPaymentMethod = false;

                foreach($paymentMethods as $paymentMethod)
                {
                    if($paymentMethod->id == $inputs['payment_method'])
                    {
                        $validPaymentMethod = $paymentMethod;
                        $payment = Payment::getPayments($paymentMethod->code);

                        $inputs['payment'] = $payment;
                        $inputs['valid_payment_method'] = $validPaymentMethod;

                        $payment->validatePlaceOrder($paymentMethod, $inputs, $validator);
                        break;
                    }
                }

                if($validPaymentMethod == false)
                    $validator->errors()->add('payment_method', trans('theme.invalid_payment_method'));
            });

            if($validator->passes())
            {

            }
            else
                return redirect()->action('Frontend\OrderController@placeOrder')->withErrors($validator)->withInput();
        }

        return view('frontend.orders.place_order', [
            'cart' => $cart,
            'paymentMethods' => $paymentMethods,
        ]);
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

        self::$fullCart = $fullCart;

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

    protected static function deleteCookieCartToken()
    {
        Cookie::queue(Cookie::forget(Cart::CART_TOKEN_COOKIE_NAME));
    }

    public static function getFullCart()
    {
        if(empty(self::$fullCart))
        {
            $cart = self::getCart();

            if(!empty($cart->cartItems))
                $cart->save();

            return self::generateFullCart($cart);
        }
        else
            return self::$fullCart;
    }
}