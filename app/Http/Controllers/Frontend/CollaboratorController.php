<?php

namespace App\Http\Controllers\Frontend;

use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Libraries\Helpers\Utility;
use App\Models\Course;

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

    public function adminCourse(Request $request)
    {
        $builder = Course::with(['promotionPrice' => function($query) {
            $query->select('course_id', 'status', 'price', 'start_time', 'end_time');
        }])->select('course.id', 'course.name', 'course.name_en', 'course.price', 'course.image', 'course.slug', 'course.slug_en', 'course.bought_count', 'course.view_count')
            ->where('course.status', Course::STATUS_PUBLISH_DB)
            ->where('course.category_status', Utility::ACTIVE_DB)
            ->orderBy('course.published_at', 'desc');

        $courses = $builder->paginate(Utility::FRONTEND_ROWS_PER_PAGE);

        return view('frontend.collaborators.admin_course', [
            'courses' => $courses,
        ]);
    }
}