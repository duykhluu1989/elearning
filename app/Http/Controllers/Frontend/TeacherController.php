<?php

namespace App\Http\Controllers\Frontend;

use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class TeacherController extends Controller
{
    public function editTeacher()
    {
        $user = auth()->user();

        return view('frontend.teachers.edit_teacher', [
            'user' => $user,
        ]);
    }

    public function adminCourseQuestion(Request $request)
    {

    }
}