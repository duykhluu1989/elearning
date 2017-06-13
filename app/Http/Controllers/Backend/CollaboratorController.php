<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Libraries\Helpers\Utility;
use App\Models\User;

class CollaboratorController extends Controller
{
    public function editCollaborator(Request $request, $id)
    {
        Utility::setBackUrlCookie($request, '/admin/userCollaborator');

        $collaborator = User::select('id')
            ->where('collaborator', Utility::ACTIVE_DB)
            ->with('collaboratorInformation')
            ->find($id);

        if(empty($collaborator))
            return view('backend.errors.404');

        return view('backend.collaborators.edit_collaborator', ['collaborator' => $collaborator]);
    }
}