<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Libraries\Widgets\GridView;
use App\Libraries\Helpers\Html;
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
                    'admin' => true,
                    'status' => User::STATUS_ACTIVE_DB,
                ];

                $remember = false;
                if(isset($inputs['remember']))
                    $remember = true;

                if(auth()->attempt($credentials, $remember))
                    return redirect()->action('Backend\HomeController@home');
                else
                    return redirect()->action('Backend\UserController@login')->withErrors(['email' => 'Email or Password is not correct'])->withInput($request->except('password'));
            }
            else
                return redirect()->action('Backend\UserController@login')->withErrors($validator)->withInput($request->except('password'));
        }

        return view('backend.users.login');
    }

    public function logout()
    {
        auth()->logout();

        return redirect()->action('Backend\UserController@login');
    }

    public function adminUser()
    {
        $dataProvider = User::select('id', 'username', 'email', 'status')->where('admin', true)->paginate(GridView::ROWS_PER_PAGE);

        $columns = [
            [
                'title' => 'Tên Tài Khoản',
                'data' => function($row) {
                    echo Html::a($row->username, [
                        'href' => action('Backend\UserController@editUser', ['id' => $row->id]),
                    ]);
                },
            ],
            [
                'title' => 'Email',
                'data' => 'email',
            ],
            [
                'title' => 'Trạng Thái',
                'data' => function($row) {
                    $status = User::getUserStatus($row->status);
                    if($row->status == User::STATUS_ACTIVE_DB)
                        echo Html::span($status, ['class' => 'text-green']);
                    else
                        echo Html::span($status, ['class' => 'text-red']);
                },
            ],
        ];

        $gridView = new GridView($dataProvider, $columns);
        $gridView->setCheckbox();

        return view('backend.users.admin_user',[
            'gridView' => $gridView,
        ]);
    }

    public function createUser(Request $request)
    {
        $user = new User();
        $user->status = User::STATUS_ACTIVE_DB;
        $user->admin = User::STATUS_ACTIVE_DB;

        return $this->saveUser($request, $user);
    }

    public function editUser(Request $request, $id)
    {
        $user = User::find($id);

        if(empty($user))
            return view('backend.errors.404');

        return $this->saveUser($request, $user, false);
    }

    protected function saveUser($request, $user, $create = true)
    {
        if($request->isMethod('post'))
        {
            $inputs = $request->all();

            $validator = Validator::make($inputs, [

            ]);

            if($validator->passes())
            {


                $user->save();

                return redirect()->action('Backend\UserController@editUser', ['id' => $user->id])->with('message', 'Success');
            }
            else
            {
                if($create == true)
                    return redirect()->action('Backend\UserController@createUser')->withErrors($validator)->withInput();
                else
                    return redirect()->action('Backend\UserController@editUser', ['id' => $user->id])->withErrors($validator)->withInput();
            }
        }

        if($create == true)
        {
            return view('backend.users.create_user', [
                'user' => $user,
            ]);
        }
        else
        {
            return view('backend.users.edit_user', [
                'user' => $user,
            ]);
        }
    }

    public function adminUserStudent()
    {
        $dataProvider = User::select('id', 'username', 'email', 'status')->where('admin', false)->paginate(GridView::ROWS_PER_PAGE);

        $columns = [
            [
                'title' => 'Tên Tài Khoản',
                'data' => function($row) {
                    echo Html::a($row->username, [
                        'href' => action('Backend\UserController@editUser', ['id' => $row->id]),
                    ]);
                },
            ],
            [
                'title' => 'Email',
                'data' => 'email',
            ],
            [
                'title' => 'Tình Trạng',
                'data' => function($row) {
                    echo User::getUserStatus($row->status);
                },
            ],
        ];

        $gridView = new GridView($dataProvider, $columns);
        $gridView->setCheckbox();

        return view('backend.users.admin_user_student',[
            'gridView' => $gridView,
        ]);
    }
}