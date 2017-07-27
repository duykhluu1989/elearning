<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Libraries\Helpers\Utility;
use App\Models\User;
use App\Models\Collaborator;
use App\Models\Setting;

class CollaboratorController extends Controller
{
    public function editCollaborator(Request $request, $id)
    {
        Utility::setBackUrlCookie($request, '/admin/userCollaborator');

        $collaborator = User::select('id', 'username')
            ->where('collaborator', Utility::ACTIVE_DB)
            ->with(['collaboratorInformation.parentCollaborator' => function($query) {
                $query->select('id', 'user_id');
            }, 'collaboratorInformation.parentCollaborator.user' => function($query) {
                $query->select('id', 'username');
            }])
            ->find($id);

        if(empty($collaborator))
            return view('backend.errors.404');

        if($request->isMethod('post'))
        {
            $inputs = $request->all();

            $validator = Validator::make($inputs, [
                'create_discount_percent' => 'required|integer|min:1|max:99',
                'commission_percent' => 'required|integer|min:1|max:99',
            ]);

            $validator->after(function($validator) use(&$inputs, $collaborator) {
                if(!empty($inputs['parent_username']))
                {
                    $user = User::select('id')
                        ->with(['collaboratorInformation' => function($query) {
                            $query->select('id', 'user_id');
                        }])
                        ->where('username', $inputs['parent_username'])
                        ->first();

                    if(!empty($user))
                    {
                        if(!empty($user->collaboratorInformation))
                        {
                            if($user->collaboratorInformation->rank->code != Setting::COLLABORATOR_MANAGER)
                            {
                                $tempParentCollaborator = $user->collaboratorInformation;

                                do
                                {
                                    if($tempParentCollaborator->id == $collaborator->collaboratorInformation->id)
                                    {
                                        $validator->errors()->add('parent_username', 'Quản Lý Không Được Là Cộng Tác Viên Cấp Dưới Của Chính Nó');
                                        break;
                                    }

                                    $tempParentCollaborator = $tempParentCollaborator->parentCollaborator;
                                }
                                while(!empty($tempParentCollaborator));

                                $inputs['parent_id'] = $user->collaboratorInformation->id;
                            }
                            else
                                $validator->errors()->add('parent_username', 'Cộng Tác Viên Không Phải Cấp Quản Lý');
                        }
                        else
                            $validator->errors()->add('parent_username', 'Thành Viên Không Phải Là Cộng Tác Viên');
                    }
                    else
                        $validator->errors()->add('parent_username', 'Cộng Tác Viên Không Tồn Tại');
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

                $collaborator->collaboratorInformation->status = isset($inputs['status']) ? Collaborator::STATUS_ACTIVE_DB : Collaborator::STATUS_INACTIVE_DB;
                $collaborator->collaboratorInformation->rank_id = $inputs['rank_id'];
                $collaborator->collaboratorInformation->create_discount_percent = $inputs['create_discount_percent'];
                $collaborator->collaboratorInformation->commission_percent = $inputs['commission_percent'];
                $collaborator->collaboratorInformation->save();

                return redirect()->action('Backend\CollaboratorController@editCollaborator', ['id' => $collaborator->id])->with('messageSuccess', 'Thành Công');
            }
            else
                return redirect()->action('Backend\CollaboratorController@editCollaborator', ['id' => $collaborator->id])->withErrors($validator)->withInput();
        }

        return view('backend.collaborators.edit_collaborator', ['collaborator' => $collaborator]);
    }
}