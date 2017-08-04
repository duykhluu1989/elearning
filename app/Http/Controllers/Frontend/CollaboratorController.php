<?php

namespace App\Http\Controllers\Frontend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CollaboratorController extends Controller
{
    public function editCollaborator()
    {
        $user = auth()->user();

        return view('frontend.collaborators.edit_collaborator', [
            'user' => $user,
        ]);
    }
}