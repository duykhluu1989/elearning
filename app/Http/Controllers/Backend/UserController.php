<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Libraries\Widgets\GridView;
use App\Libraries\Helpers\Html;
use App\Models\User;
use App\Models\Role;
use App\Models\UserRole;

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

        if($request->isMethod('post'))
        {
            $inputs = $request->all();

            $validator = Validator::make($inputs, [
                'username' => 'required|alpha_dash',
                'email' => 'required|email|unique:user,email',
                'password' => 'required|alpha_dash|min:6',
                're_password' => 'required|alpha_dash|min:6|same:password',
                'roles' => 'required',
            ]);

            if($validator->passes())
            {
                $user->username = $inputs['username'];
                $user->email = $inputs['email'];
                $user->status = $inputs['status'];
                $user->admin = $inputs['admin'];
                $user->created_at = date('Y-m-d H:i:s');
                $user->password = Hash::make($inputs['password']);
                $user->save();

                foreach($inputs['roles'] as $roleId)
                {
                    $userRole = new UserRole();
                    $userRole->user_id = $user->id;
                    $userRole->role_id = $roleId;
                    $userRole->save();
                }

                return redirect()->action('Backend\UserController@editUser', ['id' => $user->id])->with('message', 'Success');
            }
            else
                return redirect()->action('Backend\UserController@createUser')->withErrors($validator)->withInput();
        }

        $roles = Role::pluck('name', 'id');

        return view('backend.users.create_user', [
            'user' => $user,
            'roles' => $roles,
        ]);
    }

    public function editUser(Request $request, $id)
    {
        $user = User::with('userRoles', 'profile')->find($id);

        if(empty($user))
            return view('backend.errors.404');

        $roles = Role::pluck('name', 'id');

        return view('backend.users.edit_user', [
            'user' => $user,
            'roles' => $roles,
        ]);
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