<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Libraries\Helpers\Utility;
use App\Models\User;
use App\Models\Collaborator;

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

            if($validator->passes())
            {
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