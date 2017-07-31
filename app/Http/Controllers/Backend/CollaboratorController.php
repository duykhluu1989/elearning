<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Libraries\Widgets\GridView;
use App\Libraries\Helpers\Utility;
use App\Models\User;
use App\Models\Collaborator;
use App\Models\Setting;
use App\Models\CollaboratorTransaction;

class CollaboratorController extends Controller
{
    public function editCollaborator(Request $request, $id)
    {
        Utility::setBackUrlCookie($request, '/admin/userCollaborator?');

        $collaborator = User::select('id', 'username')
            ->with(['collaboratorInformation.parentCollaborator' => function($query) {
                $query->select('id', 'user_id');
            }, 'collaboratorInformation.parentCollaborator.user' => function($query) {
                $query->select('id', 'email');
            }, 'collaboratorInformation.parentCollaborator.user.profile' => function($query) {
                $query->select('user_id', 'name');
            }])
            ->find($id);

        if(empty($collaborator) || empty($collaborator->collaboratorInformation))
            return view('backend.errors.404');

        if($request->isMethod('post'))
        {
            $inputs = $request->all();

            $validator = Validator::make($inputs, [
                'create_discount_percent' => 'required|integer|min:1|max:99',
                'commission_percent' => 'required|integer|min:1|max:99',
                'bank_number' => 'nullable|numeric',
            ]);

            $validator->after(function($validator) use(&$inputs, $collaborator) {
                if(!empty($inputs['parent_user_name']))
                {
                    $userNameParts = explode(' - ', $inputs['parent_user_name']);

                    if(count($userNameParts) == 2)
                    {
                        $user = User::with(['collaboratorInformation' => function($query) {
                            $query->select('id', 'user_id', 'rank_id', 'parent_id');
                        }])->select('user.id')
                            ->join('profile', 'user.id', '=', 'profile.user_id')
                            ->where('user.email', $userNameParts[1])
                            ->where('profile.name', $userNameParts[0])
                            ->first();

                        if(!empty($user))
                        {
                            if(!empty($user->collaboratorInformation))
                            {
                                if($user->collaboratorInformation->rank->code == Setting::COLLABORATOR_MANAGER)
                                {
                                    $tempParentCollaborator = $user->collaboratorInformation;

                                    do
                                    {
                                        if($tempParentCollaborator->id == $collaborator->collaboratorInformation->id)
                                        {
                                            $validator->errors()->add('parent_user_name', 'Quản Lý Không Được Là Cộng Tác Viên Cấp Dưới Của Chính Nó');
                                            break;
                                        }

                                        $tempParentCollaborator = $tempParentCollaborator->parentCollaborator;
                                    }
                                    while(!empty($tempParentCollaborator));

                                    $inputs['parent_id'] = $user->collaboratorInformation->id;
                                }
                                else
                                    $validator->errors()->add('parent_user_name', 'Cộng Tác Viên Không Phải Cấp Quản Lý');
                            }
                            else
                                $validator->errors()->add('parent_user_name', 'Thành Viên Không Phải Là Cộng Tác Viên');
                        }
                        else
                            $validator->errors()->add('parent_user_name', 'Cộng Tác Viên Không Tồn Tại');
                    }
                    else
                        $validator->errors()->add('parent_user_name', 'Cộng Tác Viên Không Tồn Tại');
                }

                $settings = Setting::getSettings(Setting::CATEGORY_COLLABORATOR_DB);

                switch($inputs['rank_id'])
                {
                    case $settings[Setting::COLLABORATOR_SILVER]->id:

                        $settingValue = json_decode($settings[\App\Models\Setting::COLLABORATOR_GOLD]->value, true);

                        break;

                    case $settings[Setting::COLLABORATOR_GOLD]->id:

                        $settingValue = json_decode($settings[\App\Models\Setting::COLLABORATOR_DIAMOND]->value, true);

                        break;

                    case $settings[Setting::COLLABORATOR_DIAMOND]->id:

                        $settingValue = json_decode($settings[\App\Models\Setting::COLLABORATOR_MANAGER]->value, true);

                        break;

                    default:

                        $settingValue[Collaborator::DISCOUNT_ATTRIBUTE] = 99;
                        $settingValue[Collaborator::COMMISSION_ATTRIBUTE] = 99;

                        break;
                }

                if($inputs['create_discount_percent'] > $settingValue[Collaborator::DISCOUNT_ATTRIBUTE])
                    $validator->errors()->add('create_discount_percent', 'Mức Giảm Giá Được Tạo Cao Hơn Mức Cho Phép');
                if($inputs['commission_percent'] > $settingValue[Collaborator::COMMISSION_ATTRIBUTE])
                    $validator->errors()->add('commission_percent', 'Mức Hoa Hồng Được Hưởng Cao Hơn Mức Cho Phép');
            });

            if($validator->passes())
            {
                if(!empty($inputs['parent_id']))
                    $collaborator->collaboratorInformation->parent_id = $inputs['parent_id'];
                else
                    $collaborator->collaboratorInformation->parent_id = null;

                $collaborator->collaboratorInformation->status = isset($inputs['status']) ? Collaborator::STATUS_ACTIVE_DB : Collaborator::STATUS_INACTIVE_DB;
                $collaborator->collaboratorInformation->rank_id = $inputs['rank_id'];
                $collaborator->collaboratorInformation->create_discount_percent = $inputs['create_discount_percent'];
                $collaborator->collaboratorInformation->commission_percent = $inputs['commission_percent'];
                $collaborator->collaboratorInformation->bank = $inputs['bank'];
                $collaborator->collaboratorInformation->bank_holder = $inputs['bank_holder'];
                $collaborator->collaboratorInformation->bank_number = $inputs['bank_number'];
                $collaborator->collaboratorInformation->save();

                return redirect()->action('Backend\CollaboratorController@editCollaborator', ['id' => $collaborator->id])->with('messageSuccess', 'Thành Công');
            }
            else
                return redirect()->action('Backend\CollaboratorController@editCollaborator', ['id' => $collaborator->id])->withErrors($validator)->withInput();
        }

        return view('backend.collaborators.edit_collaborator', ['collaborator' => $collaborator]);
    }

    public function adminCollaboratorTransaction(Request $request, $id)
    {
        Utility::setBackUrlCookie($request, '/admin/userCollaborator?');

        $collaborator = User::select('id', 'username')
            ->with(['collaboratorInformation' => function($query) {
                $query->select('user_id');
            }])->find($id);

        if(empty($collaborator) || empty($collaborator->collaboratorInformation))
            return view('backend.errors.404');

        $dataProvider = CollaboratorTransaction::with(['order' => function($query) {
            $query->select('id', 'number');
        }, 'downlineCollaborator' => function($query) {
            $query->select('id');
        }, 'downlineCollaborator.profile' => function($query) {
            $query->select('user_id', 'name');
        }])->select('collaborator_transaction.collaborator_id', 'collaborator_transaction.order_id', 'collaborator_transaction.type', 'collaborator_transaction.commission_percent', 'collaborator_transaction.commission_amount', 'collaborator_transaction.created_at', 'collaborator_transaction.downline_collaborator_id')
            ->where('collaborator_transaction.collaborator_id', $id)
            ->orderBy('collaborator_transaction.id', 'desc');

        $inputs = $request->all();

        if(count($inputs) > 0)
        {
            if(!empty($inputs['order_number']))
            {
                $dataProvider->join('order', 'collaborator_transaction.order_id', '=', 'order.id')
                    ->where('order.number', 'like', '%' . $inputs['order_number'] . '%');
            }

            if(isset($inputs['type']) && $inputs['type'] !== '')
                $dataProvider->where('collaborator_transaction.type', $inputs['type']);
        }

        $dataProvider = $dataProvider->paginate(GridView::ROWS_PER_PAGE);

        $columns = [
            [
                'title' => 'Thời Gian',
                'data' => 'created_at',
            ],
            [
                'title' => 'Đơn Hàng',
                'data' => function($row) {
                    if(!empty($row->order))
                        echo $row->order->number;
                },
            ],
            [
                'title' => 'Loại',
                'data' => function($row) {
                    echo CollaboratorTransaction::getTransactionType($row->type);
                },
            ],
            [
                'title' => 'Tỉ Lệ',
                'data' => function($row) {
                    if(!empty($row->commission_percent))
                        echo $row->commission_percent . ' %';
                },
            ],
            [
                'title' => 'Tiền',
                'data' => function($row) {
                    echo Utility::formatNumber($row->commission_amount) . ' VND';
                },
            ],
            [
                'title' => 'CTV Cấp Dưới',
                'data' => function($row) {
                    if(!empty($row->downlineCollaborator))
                        echo $row->downlineCollaborator->profile->name;
                },
            ],
        ];

        $gridView = new GridView($dataProvider, $columns);
        $gridView->setFilters([
            [
                'title' => 'Đơn Hàng',
                'name' => 'order_number',
                'type' => 'input',
            ],
            [
                'title' => 'Loại',
                'name' => 'type',
                'type' => 'select',
                'options' => CollaboratorTransaction::getTransactionType(),
            ],
        ]);
        $gridView->setFilterValues($inputs);

        return view('backend.collaborators.admin_collaborator_transaction', [
            'collaborator' => $collaborator,
            'gridView' => $gridView,
        ]);
    }
}