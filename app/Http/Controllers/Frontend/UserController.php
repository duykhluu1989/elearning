<?php

namespace App\Http\Controllers\Frontend;

use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Libraries\Helpers\Utility;
use App\Models\User;

class UserController extends Controller
{
    public function login(Request $request)
    {
        if($request->isMethod('post'))
        {
            $inputs = $request->all();

            $validator = Validator::make($inputs, [
                'email' => 'required|email',
                'password' => 'required',
            ]);

            if($validator->passes())
            {
                $credentials = [
                    'email' => $inputs['email'],
                    'password' => $inputs['password'],
                    'status' => Utility::ACTIVE_DB,
                ];

                $remember = false;
                if(isset($inputs['remember']))
                    $remember = true;

                if(auth()->attempt($credentials, $remember))
                    return redirect()->action('Frontend\HomeController@home');
                else
                    return redirect()->action('Frontend\UserController@login')->withErrors(['email' => 'Email or Password is not correct'])->withInput($request->except('password'));
            }
            else
                return redirect()->action('Frontend\UserController@login')->withErrors($validator)->withInput($request->except('password'));
        }
    }

    public function logout()
    {
        auth()->logout();

        return redirect()->action('Frontend\HomeController@home');
    }
}