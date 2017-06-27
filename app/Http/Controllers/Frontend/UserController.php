<?php

namespace App\Http\Controllers\Frontend;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Libraries\Helpers\Utility;
use App\Models\User;
use App\Models\Profile;

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

    public function register(Request $request)
    {
        $inputs = $request->all();

        $validator = Validator::make($inputs, [
            'username' => 'required||alpha_dash|unique:user,username',
            'email' => 'required|email|unique:user,email',
            'password' => 'required|alpha_dash|min:6',
        ]);

        if($validator->passes())
        {
            try
            {
                DB::beginTransaction();

                $user = new User();
                $user->username = $inputs['username'];
                $user->email = $inputs['email'];
                $user->status = Utility::ACTIVE_DB;
                $user->admin = Utility::INACTIVE_DB;
                $user->collaborator = Utility::INACTIVE_DB;
                $user->teacher = Utility::INACTIVE_DB;
                $user->expert = Utility::INACTIVE_DB;
                $user->created_at = date('Y-m-d H:i:s');
                $user->password = Hash::make($inputs['password']);
                $user->save();

                $profile = new Profile();
                $profile->user_id = $user->id;
                $profile->save();

                DB::commit();

                auth()->login($user);

                return 'Success';
            }
            catch(\Exception $e)
            {
                DB::rollBack();

                return json_encode(['username' => $e->getMessage()]);
            }
        }
        else
            return json_encode($validator->errors()->messages());
    }
}