<?php

namespace App\RedisModels;

use Illuminate\Support\Facades\Redis;
use App\Libraries\Helpers\Utility;

class Cart
{
    protected $token;
    protected $created_at;
    protected $expired_at;
    protected $cartItems;

    const REDIS_KEY = 'cart';
    const CART_TOKEN_COOKIE_NAME = 'cart_token';

    public function __get($attribute)
    {
        return isset($this->$attribute) ? $this->$attribute : null;
    }

    public function addCartItem($courseId)
    {
        if(empty($this->cartItems) || !in_array($courseId, $this->cartItems))
            $this->cartItems[] = $courseId;
    }

    public function deleteCartItem($courseId)
    {
        if(!empty($this->cartItems))
        {
            $key = array_search($courseId, $this->cartItems);

            if($key !== false)
                unset($this->cartItems[$key]);
        }
    }

    public function save()
    {
        $time = time();

        if(empty($this->token))
        {
            $this->token = $this->generateToken();
            $this->created_at = $time;
        }

        $this->expired_at = $time + Utility::SECOND_ONE_HOUR;

        $encodedData = $this->encoded();

        return Redis::command('setex', [static::REDIS_KEY . '_' . $this->token, Utility::SECOND_ONE_HOUR, $encodedData]);
    }

    public static function find($token)
    {
        $encodeData = Redis::command('get', [static::REDIS_KEY . '_' . $token]);

        if(!empty($encodeData))
        {
            $cart = new Cart();
            $cart->decoded($encodeData);

            return $cart;
        }

        return null;
    }

    protected function generateToken()
    {
        return md5(microtime(true));
    }

    protected function encoded()
    {
        $encodedData = [
            'token' => $this->token,
            'created_at' => $this->created_at,
            'expired_at' => $this->expired_at,
            'cartItems' => $this->cartItems,
        ];

        return json_encode($encodedData);
    }

    protected function decoded($encodedData)
    {
        $encodedData = json_decode($encodedData, true);

        $this->token = $encodedData['token'];
        $this->created_at = $encodedData['created_at'];
        $this->expired_at = $encodedData['expired_at'];
        $this->cartItems = $encodedData['cartItems'];
    }

    public function delete()
    {
        Redis::command('del', [static::REDIS_KEY . '_' . $this->token]);
    }
}