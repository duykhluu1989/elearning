<?php

namespace App\Http\Controllers\Frontend;

use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Libraries\Helpers\Utility;
use App\Libraries\Helpers\Area;
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
            'first_name' => 'required|string|max:100',
            'last_name' => 'nullable|string|max:100',
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
                $profile->first_name = $inputs['first_name'];
                $profile->last_name = $inputs['last_name'];
                $profile->name = trim($profile->last_name . ' ' . $profile->first_name);
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

                    $openId = json_encode($user->open_id, true);
                    $openId['facebook'] = $userNode['id'];
                    $user->open_id = json_encode($openId);

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
                $openId = json_decode($user->open_id, true);
                $openId['facebook'] = $userNode['id'];
                $user->open_id = json_encode($openId);

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
                'iss' => request()->getUri(),
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

    public function editAccount(Request $request)
    {
        $user = auth()->user();

        if($request->isMethod('post'))
        {
            $inputs = $request->all();

            $validator = Validator::make($inputs, [
                'old_password' => 'required_with:new_password|alpha_dash',
                'new_password' => 'required_with:old_password|alpha_dash|min:6|max:32',
                're_new_password' => 'nullable|alpha_dash|min:6|max:32|same:new_password',
                'username' => 'required|unique:user,username,' . $user->id,
                'email' => 'required|unique:user,email,' . $user->id,
                'avatar' => 'mimes:' . implode(',', Utility::getValidImageExt()),
                'first_name' => 'required|max:100',
                'last_name' => 'nullable|max:100',
                'phone' => 'nullable|numeric',
                'birthday' => 'nullable|date',
                'address' => 'nullable|max:255',
                'title' => 'nullable|max:255',
            ]);

            $validator->after(function($validator) use(&$inputs, $user) {
                if(!empty($inputs['old_password']))
                {
                    if(Hash::check($inputs['old_password'], $user->password) == false)
                        $validator->errors()->add('old_password', trans('theme.old_password_error'));
                }
            });

            if($validator->passes())
            {
                try
                {
                    DB::beginTransaction();

                    if(isset($inputs['avatar']))
                    {
                        $savePath = User::AVATAR_UPLOAD_PATH . '/' . $user->id;

                        list($imagePath, $imageUrl) = Utility::saveFile($inputs['avatar'], $savePath, Utility::getValidImageExt());

                        if(!empty($imagePath) && !empty($imageUrl))
                        {
                            Utility::resizeImage($imagePath, 200);

                            if(!empty($user->avatar))
                                Utility::deleteFile($user->avatar);

                            $user->avatar = $imageUrl;
                        }
                    }

                    if(!empty($inputs['new_password']))
                        $user->password = Hash::make($inputs['new_password']);

                    $user->save();

                    $user->profile->first_name = $inputs['first_name'];
                    $user->profile->last_name = $inputs['last_name'];
                    $user->profile->title = $inputs['title'];
                    $user->profile->name = trim($user->profile->last_name . ' ' . $user->profile->first_name);
                    $user->profile->gender = $inputs['gender'];
                    $user->profile->birthday = $inputs['birthday'];
                    $user->profile->phone = $inputs['phone'];
                    $user->profile->address = $inputs['address'];
                    $user->profile->province = Area::$provinces[$inputs['province']]['name'];
                    $user->profile->district = Area::$provinces[$inputs['province']]['cities'][$inputs['district']];
                    $user->profile->save();

                    DB::commit();

                    return redirect()->action('Frontend\UserController@editAccount')->with('messageSuccess', 'Thành Công');
                }
                catch(\Exception $e)
                {
                    DB::rollBack();

                    return redirect()->action('Frontend\UserController@editAccount')->withInput()->with('messageError', $e->getMessage());
                }
            }
            else
                return redirect()->action('Frontend\UserController@editAccount')->withErrors($validator)->withInput();
        }

        return view('frontend.users.edit_account', [
            'user' => $user,
        ]);
    }
}