<?php

namespace App\Http\Controllers\Frontend;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Libraries\Helpers\Utility;
use App\Libraries\Payments\Payment;
use App\Libraries\Helpers\Area;
use App\Models\Course;
use App\Models\Category;
use App\Models\PaymentMethod;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\UserCourse;
use App\Models\OrderAddress;
use App\Models\Discount;
use App\Models\User;
use App\Models\Collaborator;
use App\Models\Student;
use App\RedisModels\Cart;

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

            return view('frontend.orders.partials.cart', [
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
        if($request->isMethod('post'))
        {
            $inputs = $request->all();

            $cart = self::getCart();

            $validator = Validator::make($inputs, [
                'payment_method' => 'required',
                'discount_code' => 'nullable|alpha_num|max:255'
            ]);

            $validator->after(function($validator) use(&$inputs, $cart) {
                if(empty($cart->cartItems))
                    $validator->errors()->add('cart', trans('theme.empty_cart'));
                else
                {
                    $userCourses = UserCourse::with(['course' => function($query) {
                        $query->select('id', 'name', 'name_ne');
                    }])->select('course_id')->where('user_id', auth()->user()->id)->whereIn('course_id', $cart->cartItems)->get();

                    $boughtCourses = '';

                    foreach($userCourses as $userCourse)
                    {
                        $cart->deleteCartItem($userCourse->course_id);

                        if($boughtCourses == '')
                            $boughtCourses .= Utility::getValueByLocale($userCourse->course, 'name');
                        else
                            $boughtCourses .= ', ' . Utility::getValueByLocale($userCourse->course, 'name');
                    }

                    if(empty($cart->cartItems))
                    {
                        $cart->delete();

                        self::deleteCookieCartToken();
                    }

                    if($boughtCourses != '')
                        $validator->errors()->add('cart', trans('theme.bought_courses', ['courses' => $boughtCourses]));

                    $paymentMethod = PaymentMethod::select('id', 'name', 'name_en', 'type', 'detail', 'code')
                        ->where('status', Utility::ACTIVE_DB)
                        ->find($inputs['payment_method']);

                    if(empty($paymentMethod))
                        $validator->errors()->add('payment_method', trans('theme.invalid_payment_method'));
                    else
                    {
                        $payment = Payment::getPayments($paymentMethod->code);

                        $inputs['payment'] = $payment;
                        $inputs['order_payment_method'] = $paymentMethod;

                        $payment->validatePlaceOrder($paymentMethod, $inputs, $validator, $cart);
                    }

                    if(!empty($inputs['discount_code']))
                    {
                        $result = Discount::calculateDiscountPrice($inputs['discount_code'], $cart, auth()->user());

                        if($result['status'] == 'error')
                            $validator->errors()->add('discount_code', $result['message']);
                        else if($result['discountPrice'] > 0)
                        {
                            $inputs['discount'] = $result['discount'];
                            $inputs['discount_price'] = $result['discountPrice'];
                        }
                    }
                }
            });

            if($validator->passes())
            {
                $referral = null;

                if($request->hasCookie(Utility::REFERRAL_COOKIE_NAME))
                {
                    $referralData = json_decode(request()->cookie(Utility::REFERRAL_COOKIE_NAME), true);

                    if(!is_array($referralData))
                        $referralData = array();

                    if(isset($referralData['referral']) && isset($referralData['course']))
                    {
                        $referral = User::select('user.id')
                            ->join('collaborator', 'user.id', '=', 'collaborator.user_id')
                            ->where('user.status', Utility::ACTIVE_DB)
                            ->where('collaborator.status', Collaborator::STATUS_ACTIVE_DB)
                            ->where('collaborator.code', $referralData['referral'])
                            ->first();

                        if(!empty($referral) && $referral->id == auth()->user()->id)
                        {
                            $referral = null;
                            $referralData = array();
                        }
                    }
                }
                else
                    $referralData = array();

                $courses = Course::with(['promotionPrice' => function($query) {
                    $query->select('course_id', 'status', 'price', 'start_time', 'end_time');
                }])->select('id', 'name', 'name_en', 'price', 'point_price')
                    ->whereIn('id', $cart->cartItems)
                    ->get();

                $totalPrice = 0;
                $totalPointPrice = 0;

                if(isset($inputs['discount_price']))
                    $discountPrice = $inputs['discount_price'];
                else
                    $discountPrice = 0;

                foreach($courses as $course)
                {
                    if($course->validatePromotionPrice())
                        $totalPrice += $course->promotionPrice->price;
                    else
                        $totalPrice += $course->price;

                    if(!empty($course->point_price))
                        $totalPointPrice += $course->point_price;
                }

                $totalPrice -= $discountPrice;

                try
                {
                    DB::beginTransaction();

                    $order = new Order();
                    $order->user_id = auth()->user()->id;
                    $order->created_at = date('Y-m-d H:i:s');
                    $order->payment_method_id = $inputs['payment_method'];
                    $order->payment_status = Order::PAYMENT_STATUS_PENDING_DB;
                    $order->total_price = $totalPrice;
                    $order->total_discount_price = $discountPrice;
                    $order->total_point_price = $totalPointPrice;

                    if(!empty($inputs['note']))
                        $order->note = $inputs['note'];

                    if(isset($inputs['discount']))
                    {
                        $discount = $inputs['discount'];
                        $discount->used_count += 1;
                        $discount->save();

                        $order->discount_id = $discount->id;
                    }

                    if(!empty($referral))
                        $order->referral_id = $referral->id;

                    $order->save();

                    if(empty(auth()->user()->studentInformation))
                    {
                        $student = new Student();
                        $student->user_id = auth()->user()->id;
                        $student->order_count = 1;
                        $student->save();
                    }

                    foreach($courses as $course)
                    {
                        $orderItem = new OrderItem();
                        $orderItem->order_id = $order->id;
                        $orderItem->course_id = $course->id;

                        if($course->validatePromotionPrice())
                            $orderItem->price = $course->promotionPrice->price;
                        else
                            $orderItem->price = $course->price;

                        if(empty($course->point_price))
                            $orderItem->point_price = 0;
                        else
                            $orderItem->point_price = $course->point_price;

                        if(isset($referralData['course']) && $course->id == $referralData['course'])
                            $orderItem->referral_item = Utility::ACTIVE_DB;

                        $orderItem->save();
                    }

                    if(isset($inputs['name']))
                    {
                        $orderAddress = new OrderAddress();
                        $orderAddress->order_id = $order->id;
                        $orderAddress->name = $inputs['name'];
                        $orderAddress->email = $inputs['email'];
                        $orderAddress->phone = $inputs['phone'];
                        $orderAddress->address = $inputs['address'];
                        $orderAddress->province = Area::$provinces[$inputs['province']]['name'];
                        $orderAddress->district = Area::$provinces[$inputs['province']]['cities'][$inputs['district']];
                        $orderAddress->save();
                    }

                    $inputs['payment']->handlePlacedOrderPayment($order);

                    DB::commit();

                    $orderThankYou = [
                        'order_number' => $order->number,
                        'payment_method' => Utility::getValueByLocale($inputs['order_payment_method'], 'name'),
                        'total_price' => $order->total_price,
                        'courses' => array(),
                    ];

                    foreach($courses as $course)
                        $orderThankYou['courses'][] = Utility::getValueByLocale($course, 'name');

                    $cart->delete();

                    self::deleteCookieCartToken();

                    if(!empty($order->referral_id))
                        Cookie::queue(Cookie::forget(Utility::REFERRAL_COOKIE_NAME));

                    return redirect()->action('Frontend\OrderController@thankYou')->with('order', json_encode($orderThankYou));
                }
                catch(\Exception $e)
                {
                    DB::rollBack();

                    return redirect()->action('Frontend\OrderController@placeOrder')->withErrors(['payment_method' => [$e->getMessage()]])->withInput();
                }
            }
            else
                return redirect()->action('Frontend\OrderController@placeOrder')->withErrors($validator)->withInput();
        }

        $cart = self::getFullCart();

        $paymentMethods = PaymentMethod::select('id', 'name', 'name_en', 'type', 'detail', 'code')
            ->where('status', Utility::ACTIVE_DB)
            ->orderBy('order', 'desc')
            ->get();

        $coupon = null;

        if($request->hasCookie(Utility::REFERRAL_COOKIE_NAME))
        {
            $referralData = json_decode(request()->cookie(Utility::REFERRAL_COOKIE_NAME), true);

            if(!is_array($referralData))
                $referralData = array();

            if(isset($referralData['coupon']))
                $coupon = $referralData['coupon'];
        }

        return view('frontend.orders.place_order', [
            'cart' => $cart,
            'paymentMethods' => $paymentMethods,
            'coupon' => $coupon,
        ]);
    }

    public function useDiscountCode(Request $request)
    {
        $inputs = $request->all();

        $cart = self::getCart();

        $validator = Validator::make($inputs, [
            'discount_code' => 'required|alpha_num|max:255',
        ]);

        $validator->after(function($validator) use(&$inputs, $cart) {
            if(empty($cart->cartItems))
                $validator->errors()->add('discount_code', trans('theme.empty_cart'));
            else
            {
                $result = Discount::calculateDiscountPrice($inputs['discount_code'], $cart, auth()->user());

                if($result['status'] == 'error')
                    $validator->errors()->add('discount_code', $result['message']);
                else
                    $inputs['discount_price'] = $result['discountPrice'];
            }
        });

        if($validator->passes())
        {
            return json_encode([
                'status' => 'success',
                'discount' => $inputs['discount_price'],
            ]);
        }
        else
            return json_encode([
                'status' => 'error',
                'message' => $validator->errors()->first('discount_code'),
            ]);
    }

    public function thankYou()
    {
        if(session('order'))
        {
            $orderThankYou = json_decode(session('order'), true);

            return view('frontend.orders.thank_you', [
                'orderThankYou' => $orderThankYou,
            ]);
        }
        else
            return redirect()->action('Frontend\OrderController@placeOrder');
    }

    public function learnCourseNow($id, $slug)
    {
        $course = Course::with(['promotionPrice' => function($query) {
            $query->select('course_id', 'status', 'price', 'start_time', 'end_time');
        }])->where('id', $id)
            ->where('status', Course::STATUS_PUBLISH_DB)
            ->where('category_status', Utility::ACTIVE_DB)
            ->where(function($query) use($slug) {
                $query->where('slug', $slug)->orWhere('slug_en', $slug);
            })->first();

        if(empty($course))
            return view('frontend.errors.404');

        $user = auth()->user();

        $learned = $user->learnCourseNow($course);

        if($learned == false)
            return view('frontend.errors.404');
        else
            return redirect()->action('Frontend\CourseController@detailCourse', ['id' => $course->id, 'slug' => Utility::getValueByLocale($course, 'slug')]);
    }

    public function getListDistrict(Request $request)
    {
        if($request->ajax() == false)
            return view('frontend.errors.404');

        $inputs = $request->all();

        $validator = Validator::make($inputs, [
            'province_code' => 'required',
        ]);

        if($validator->passes())
        {
            $provinces = Area::$provinces;

            if(isset($provinces[$inputs['province_code']]))
                return json_encode($provinces[$inputs['province_code']]['cities']);
            else
                return '';
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
            'totalPointPrice' => 0,
            'cartItems' => array(),
        ];

        if(!empty($cart->cartItems))
        {
            $courses = Course::with(['promotionPrice' => function($query) {
                $query->select('course_id', 'status', 'price', 'start_time', 'end_time');
            }])->select('id', 'name', 'name_en', 'price', 'point_price', 'image', 'slug', 'slug_en')
                ->whereIn('id', $cart->cartItems)
                ->get();

            $fullCart['countItem'] = count($courses);
            $fullCart['cartItems'] = $courses;

            foreach($fullCart['cartItems'] as $cartItem)
            {
                if($cartItem->validatePromotionPrice())
                    $fullCart['totalPrice'] += $cartItem->promotionPrice->price;
                else
                    $fullCart['totalPrice'] += $cartItem->price;

                if(!empty($cartItem->point_price))
                    $fullCart['totalPointPrice'] += $cartItem->point_price;
            }
        }

        self::$fullCart = $fullCart;

        return $fullCart;
    }

    protected static function getCookieCartToken()
    {
        return request()->cookie(Cart::CART_TOKEN_COOKIE_NAME);
    }

    public static function setCookieCartToken($token)
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