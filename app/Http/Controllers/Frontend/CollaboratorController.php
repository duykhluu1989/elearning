<?php

namespace App\Http\Controllers\Frontend;

use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Libraries\Helpers\Utility;
use App\Models\Course;
use App\Models\CollaboratorTransaction;

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
        $builder = Course::with(['category' => function($query) {
                $query->select('id', 'name', 'name_en');
            }])
            ->select('course.id', 'course.name', 'course.name_en', 'course.price', 'course.image', 'course.slug', 'course.slug_en', 'course.bought_count', 'course.view_count', 'course.category_id')
            ->where('course.status', Course::STATUS_PUBLISH_DB)
            ->where('course.category_status', Utility::ACTIVE_DB)
            ->orderBy('course.published_at', 'desc');

        $inputs = $request->all();

        if(count($inputs) > 0)
        {
            if(!empty($inputs['course']))
                $builder->where('course.name', 'like', '%' . $inputs['course'] . '%')->orWhere('course.name_en', 'like', '%' . $inputs['course'] . '%');

            if(!empty($inputs['category']))
            {
                $builder->join('category_course', 'course.id', '=', 'category_course.course_id')
                    ->join('category', 'category_course.category_id', '=', 'category.id')
                    ->where('category.name', 'like', '%' . $inputs['category'] . '%')->orWhere('category.name_en', 'like', '%' . $inputs['category'] . '%');
            }
        }

        $courses = $builder->paginate(Utility::FRONTEND_ROWS_PER_PAGE);

        return view('frontend.collaborators.admin_course', [
            'courses' => $courses,
        ]);
    }

    public function adminCollaboratorTransaction(Request $request)
    {
        $user = auth()->user();

        $builder = CollaboratorTransaction::with(['order' => function($query) {
            $query->select('id', 'number');
        }, 'order.orderItems' => function($query) {
            $query->select('order_id', 'course_id');
        }, 'order.orderItems.course' => function($query) {
            $query->select('id', 'name', 'name_en');
        }, 'downlineCollaborator' => function($query) {
            $query->select('id');
        }, 'downlineCollaborator.profile' => function($query) {
            $query->select('user_id', 'name');
        }])->select('collaborator_transaction.collaborator_id', 'collaborator_transaction.order_id', 'collaborator_transaction.type', 'collaborator_transaction.commission_percent', 'collaborator_transaction.commission_amount', 'collaborator_transaction.created_at', 'collaborator_transaction.downline_collaborator_id')
            ->where('collaborator_transaction.collaborator_id', $user->id)
            ->orderBy('collaborator_transaction.id', 'desc');

        $inputs = $request->all();

        if(count($inputs) > 0)
        {
            if(!empty($inputs['order']))
            {
                $builder->join('order', 'collaborator_transaction.order_id', '=', 'order.id')
                    ->where('order.number', 'like', '%' . $inputs['order'] . '%');
            }

            if(isset($inputs['type']) && $inputs['type'] !== '')
                $builder->where('collaborator_transaction.type', $inputs['type']);
        }

        $transactions = $builder->paginate(Utility::FRONTEND_ROWS_PER_PAGE);

        return view('frontend.collaborators.admin_collaborator_transaction', [
            'transactions' => $transactions,
        ]);
    }
}