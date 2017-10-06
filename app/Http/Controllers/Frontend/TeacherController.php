<?php

namespace App\Http\Controllers\Frontend;

use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Libraries\Helpers\Utility;
use App\Models\CourseQuestion;

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
        $user = auth()->user();

        $builder = CourseQuestion::with(['user' => function($query) {
            $query->select('id');
        }, 'user.profile' => function($query) {
            $query->select('user_id', 'name');
        }, 'course' => function($query) {
            $query->select('id', 'name', 'name_en', 'slug', 'slug_en', 'image');
        }])->join('course', 'course_question.course_id', '=', 'course.id')
            ->select('course_question.*')
            ->whereIn('course_question.status', [
                CourseQuestion::STATUS_WAIT_TEACHER_DB,
                CourseQuestion::STATUS_ACTIVE_DB,
            ])
            ->where('course.user_id', $user->id)
            ->orderBy('course_question.id', 'desc');

        $inputs = $request->all();

        if(count($inputs) > 0)
        {
            if(!empty($inputs['course']))
                $builder->where('course.name', 'like', '%' . $inputs['course'] . '%')->orWhere('course.name_en', 'like', '%' . $inputs['course'] . '%');

            if(!empty($inputs['question']))
                $builder->where('course_question.question', 'like', '%' . $inputs['question'] . '%');

            if(isset($inputs['status']) && $inputs['status'] !== '')
                $builder->where('course_question.status', $inputs['status']);
        }

        $courseQuestions = $builder->paginate(Utility::FRONTEND_ROWS_PER_PAGE);

        return view('frontend.teachers.admin_course_question', [
            'courseQuestions' => $courseQuestions,
        ]);
    }

    public function editCourseQuestion(Request $request, $id)
    {

    }
}