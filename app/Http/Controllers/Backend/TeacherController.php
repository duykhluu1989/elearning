<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Libraries\Helpers\Utility;
use App\Models\User;
use App\Models\Collaborator;

class TeacherController extends Controller
{
    public function editTeacher(Request $request, $id)
    {
        Utility::setBackUrlCookie($request, '/admin/userTeacher?');

        $teacher = User::select('id', 'username')
            ->with('teacherInformation')
            ->find($id);

        if(empty($teacher) || empty($teacher->teacherInformation))
            return view('backend.errors.404');

        if($request->isMethod('post'))
        {
            $inputs = $request->all();

            $teacher->teacherInformation->status = isset($inputs['status']) ? Collaborator::STATUS_ACTIVE_DB : Collaborator::STATUS_INACTIVE_DB;
            $teacher->teacherInformation->organization = isset($inputs['organization']) ? Utility::ACTIVE_DB : Utility::INACTIVE_DB;
            $teacher->teacherInformation->save();

            return redirect()->action('Backend\TeacherController@editTeacher', ['id' => $teacher->id])->with('messageSuccess', 'Thành Công');
        }

        return view('backend.teachers.edit_teacher', ['teacher' => $teacher]);
    }
}