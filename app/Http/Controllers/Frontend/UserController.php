<?php

namespace App\Http\Controllers\Frontend;

use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Libraries\Helpers\Utility;
use App\Models\User;
use App\Models\Profile;
use App\Models\Setting;
use Facebook\Facebook;
use Facebook\Exceptions\FacebookResponseException;
use Facebook\Exceptions\FacebookSDKException;
use Firebase\JWT\JWT;

class UserController extends Controller
{
    public function login(Request $request)
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

            if(auth()->attempt($credentials))
                return 'Success';
            else
                return json_encode(['email' => [trans('theme.sign_in_fail')]]);
        }
        else
            return json_encode(['email' => [trans('theme.sign_in_fail')]]);
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
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:user,email',
            'password' => 'required|alpha_dash|min:6|max:32',
        ]);

        if($validator->passes())
        {
            try
            {
                DB::beginTransaction();

                $user = new User();
                $user->username = explode('@', $inputs['email'])[0];
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
                $profile->first_name = $inputs['name'];
                $profile->name = $inputs['name'];
                $profile->save();

                DB::commit();

                auth()->login($user);

                return 'Success';
            }
            catch(\Exception $e)
            {
                DB::rollBack();

                return json_encode(['username' => [$e->getMessage()]]);
            }
        }
        else
            return json_encode($validator->errors()->messages());
    }

    public function loginWithFacebook(Request $request)
    {
        $inputs = $request->all();

        $validator = Validator::make($inputs, [
            'access_token' => 'required',
        ]);

        if($validator->passes())
        {
            $fb = new Facebook([
                'app_id' => Setting::getSettings(Setting::CATEGORY_SOCIAL_DB, Setting::FACEBOOK_APP_ID),
                'app_secret' => Setting::getSettings(Setting::CATEGORY_SOCIAL_DB, Setting::FACEBOOK_APP_SECRET),
                'default_graph_version' => Setting::getSettings(Setting::CATEGORY_SOCIAL_DB, Setting::FACEBOOK_GRAPH_VERSION),
            ]);

            $fb->setDefaultAccessToken($inputs['access_token']);

            try
            {
                $response = $fb->get('/me?fields=id,first_name,last_name,email,birthday,gender');
                $userNode = $response->getDecodedBody();
            }
            catch(FacebookResponseException $e)
            {
                return trans('theme.system_error');
            }
            catch(FacebookSDKException $e)
            {
                return trans('theme.system_error');
            }

            if(isset($userNode['email']))
                $email = $userNode['email'];
            else
                $email = $userNode['id'] . '@facebook.com';

            $user = User::where('email', $email)->first();

            if(!isset($user))
                $user = User::where('open_id', '"facebook":"' . $userNode['id'] . '"')->first();

            if(!isset($user))
            {
                try
                {
                    DB::beginTransaction();

                    $user = new User();
                    $user->username = explode('@', $email)[0];
                    $user->email = $email;
                    $user->status = Utility::ACTIVE_DB;
                    $user->admin = Utility::INACTIVE_DB;
                    $user->collaborator = Utility::INACTIVE_DB;
                    $user->teacher = Utility::INACTIVE_DB;
                    $user->expert = Utility::INACTIVE_DB;
                    $user->created_at = date('Y-m-d H:i:s');

                    $openId = $user->open_id;
                    $openId['facebook'] = $userNode['id'];
                    $user->open_id = $openId;

                    $user->save();

                    $profile = new Profile();
                    $profile->user_id = $user->id;
                    $profile->first_name = $userNode['first_name'];
                    $profile->last_name = $userNode['last_name'];
                    $profile->name = trim($profile->last_name . ' ' . $profile->first_name);

                    if(isset($userNode['gender']))
                    {
                        if($userNode['gender'] == 'male')
                            $profile->gender = Utility::INACTIVE_DB;
                        else if($userNode['gender'] == 'female')
                            $profile->gender = Utility::ACTIVE_DB;
                    }

                    if(isset($userNode['birthday']))
                    {
                        $birthdayTimestamp = strtotime($userNode['birthday']);

                        if($birthdayTimestamp)
                            $profile->birthday = date('Y-m-d', $birthdayTimestamp);
                    }

                    $profile->save();

                    DB::commit();
                }
                catch(\Exception $e)
                {
                    DB::rollBack();

                    return trans('theme.system_error');
                }
            }
            else if($user->status == Utility::INACTIVE_DB)
                return trans('theme.sign_in_fail');
            else if(!isset($user->open_id['facebook']))
            {
                $openId = $user->open_id;
                $openId['facebook'] = $userNode['id'];
                $user->open_id = $openId;

                $user->save();
            }

            auth()->login($user);

            return 'Success';
        }

        return '';
    }

    public function retrievePassword(Request $request)
    {
        $inputs = $request->all();

        $validator = Validator::make($inputs, [
            'email' => 'required|email',
        ]);

        if($validator->passes())
        {
            $user = User::where('email', $inputs['email'])->first();

            if(empty($user))
                return json_encode(['email' => [trans('theme.sign_in_fail')]]);

            if($user->status == Utility::INACTIVE_DB)
                return json_encode(['email' => [trans('theme.sign_in_fail')]]);

            $time = time();

            $claims = [
                'sub' => $user->id,
                'iat' => $time,
                'exp' => $time + Utility::SECOND_ONE_HOUR,
                'iss' => app('request')->getUri(),
                'jti' => md5($user->id . $time),
            ];

            $token = JWT::encode($claims, env('APP_KEY'));

            try
            {
                $user->password = $token;
                $user->save();

                $loginLink = action('Frontend\UserController@loginWithToken', ['token' => $token]);

                Mail::send('frontend.emails.retrieve_password', ['loginLink' => $loginLink], function($message) use($user) {

                    $message->from('admin@caydenthan.vn', Setting::getSettings(Setting::CATEGORY_GENERAL_DB, Setting::WEB_TITLE));
                    $message->to($user->email, $user->profile->name);
                    $message->subject(Setting::getSettings(Setting::CATEGORY_GENERAL_DB, Setting::WEB_TITLE) . ' | ' . trans('theme.retrieve_password'));

                });

                return 'Success';
            }
            catch(\Exception $e)
            {
                return json_encode(['email' => [trans('theme.system_error')]]);
            }
        }
        else
            return json_encode($validator->errors()->messages());
    }

    public function loginWithToken($token)
    {
        try
        {
            $decoded = JWT::decode($token, env('APP_KEY'), ['HS256']);

            $user = User::where('id', $decoded->sub)->where('status', Utility::ACTIVE_DB)->first();

            if(!empty($user) && $user->password == $token)
            {
                $user->password = null;
                $user->save();

                auth()->login($user);
            }

            return redirect()->action('Frontend\HomeController@home');
        }
        catch(\Exception $e)
        {
            return view('frontend.errors.404');
        }
    }
}