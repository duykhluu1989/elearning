<?php

namespace App\Http\Controllers\Frontend;

use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CollaboratorController extends Controller
{
    public function editCollaborator(Request $request)
    {
        $user = auth()->user();

        if($request->isMethod('post'))
        {
            $inputs = $request->all();

            $validator = Validator::make($inputs, [
                'bank' => 'max:255',
                'bank_holder' => 'max:255',
                'bank_number' => 'nullable|numeric',
            ]);

            if($validator->passes())
            {
                $user->collaboratorInformation->bank = $inputs['bank'];
                $user->collaboratorInformation->bank_holder = $inputs['bank_holder'];
                $user->collaboratorInformation->bank_number = $inputs['bank_number'];
                $user->collaboratorInformation->save();

                return redirect()->action('Frontend\CollaboratorController@editCollaborator')->with('messageSuccess', 'Thành Công');
            }
            else
                return redirect()->action('Frontend\CollaboratorController@editCollaborator')->withErrors($validator)->withInput();
        }

        return view('frontend.collaborators.edit_collaborator', [
            'user' => $user,
        ]);
    }
}