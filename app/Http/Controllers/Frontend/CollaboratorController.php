<?php

namespace App\Http\Controllers\Frontend;

use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Libraries\Helpers\Utility;
use App\Models\Course;
use App\Models\Collaborator;
use App\Models\CollaboratorTransaction;
use App\Models\Discount;
use App\Models\User;

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

                return redirect()->action('Frontend\CollaboratorController@editCollaborator')->with('messageSuccess', trans('theme.success'));
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
        $user = auth()->user();

        $discount = Discount::select('id', 'code', 'value')->where('collaborator_id', $user->id)
            ->first();

        if($request->isMethod('post'))
        {
            $inputs = $request->all();

            $validator = Validator::make($inputs, [
                'percent' => 'required|integer|min:1|max:' . $user->collaboratorInformation->create_discount_percent,
            ]);

            if($validator->passes())
            {
                if(!empty($discount))
                {
                    $discount->value = $inputs['percent'];
                    $discount->save();
                }

                return redirect()->action('Frontend\CollaboratorController@adminCourse')->with('messageSuccess', trans('theme.success'));
            }
            else
                return redirect()->action('Frontend\CollaboratorController@adminCourse')->withErrors($validator)->withInput();
        }

        $builder = Course::with(['category' => function($query) {
                $query->select('id', 'name', 'name_en');
            }])
            ->select('course.id', 'course.name', 'course.name_en', 'course.price', 'course.image', 'course.slug', 'course.slug_en', 'course.bought_count', 'course.category_id')
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
            'discount' => $discount,
            'user' => $user,
        ]);
    }

    public function generateDiscount()
    {
        $user = auth()->user();

        $discount = Discount::select('id')->where('collaborator_id', $user->id)
            ->first();

        if(empty($discount))
        {
            $discount = new Discount();
            $discount->code = Discount::generateCodeByNumberCharacter(20);
            $discount->status = Utility::ACTIVE_DB;
            $discount->start_time = date('Y-m-d');
            $discount->end_time = date('Y-m-d', strtotime('+ 10 years'));
            $discount->type = Discount::TYPE_PERCENTAGE_DB;
            $discount->value = $user->collaboratorInformation->commission_percent;
            $discount->created_at = date('Y-m-d H:i:s');
            $discount->collaborator_id = $user->id;
            $discount->save();

            return redirect()->action('Frontend\CollaboratorController@adminCourse')->with('messageSuccess', trans('theme.success'));
        }

        return redirect()->action('Frontend\CollaboratorController@adminCourse');
    }

    public function getLinkCourse($id)
    {
        $user = auth()->user();

        $discount = Discount::select('code', 'value')->where('collaborator_id', $user->id)
            ->first();

        $course = Course::select('id', 'name', 'name_en', 'slug', 'slug_en')
            ->where('course.status', Course::STATUS_PUBLISH_DB)
            ->where('course.category_status', Utility::ACTIVE_DB)
            ->find($id);

        if(empty($course))
            return view('frontend.errors.404');

        return view('frontend.collaborators.get_link_course', [
            'course' => $course,
            'user' => $user,
            'discount' => $discount,
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

    public function adminCollaboratorDownLine(Request $request)
    {
        $user = auth()->user();

        $builder = User::with(['collaboratorInformation' => function($query) {
            $query->select('user_id', 'code', 'rank_id', 'total_commission', 'total_revenue', 'create_discount_percent', 'commission_percent');
        }, 'profile' => function($query) {
            $query->select('user_id', 'name');
        }])->select('user.id')
            ->join('collaborator', 'user.id', '=', 'collaborator.user_id')
            ->where('collaborator.parent_id', $user->collaboratorInformation->id)
            ->orderBy('user.created_at', 'desc');

        $inputs = $request->all();

        if(count($inputs) > 0)
        {
            if(!empty($inputs['code']))
                $builder->where('collaborator.code', 'like', '%' . $inputs['code'] . '%');

            if(!empty($inputs['name']))
            {
                $builder->join('profile', 'user.id', '=', 'profile.user_id')
                    ->where('profile.name', 'like', '%' . $inputs['name'] . '%');
            }

            if(!empty($inputs['rank']))
                $builder->where('collaborator.rank_id', $inputs['rank']);
        }

        $collaborators = $builder->paginate(Utility::FRONTEND_ROWS_PER_PAGE);

        return view('frontend.collaborators.admin_collaborator_down_line', [
            'collaborators' => $collaborators,
        ]);
    }

    public function editCollaboratorDownLine(Request $request, $id)
    {
        $user = auth()->user();

        $collaborator = User::with(['collaboratorInformation' => function($query) {
            $query->select('id', 'user_id', 'code', 'rank_id', 'total_commission', 'total_revenue', 'create_discount_percent', 'commission_percent');
        }, 'profile' => function($query) {
            $query->select('user_id', 'name');
        }])->select('user.id')
            ->join('collaborator', 'user.id', '=', 'collaborator.user_id')
            ->where('collaborator.parent_id', $user->collaboratorInformation->id)
            ->where('user.id', $id)
            ->first();

        if(empty($collaborator))
            return view('frontend.errors.404');

        if($request->isMethod('post'))
        {
            $inputs = $request->all();

            $collaboratorRank = json_decode($collaborator->collaboratorInformation->rank->value, true);

            $validator = Validator::make($inputs, [
                'commission_percent' => 'required|integer|min:1|max:' . $collaboratorRank[Collaborator::COMMISSION_ATTRIBUTE],
                'create_discount_percent' => 'required|integer|min:1|max:' . $collaboratorRank[Collaborator::DISCOUNT_ATTRIBUTE],
            ]);

            if($validator->passes())
            {
                $collaborator->collaboratorInformation->commission_percent = $inputs['commission_percent'];
                $collaborator->collaboratorInformation->create_discount_percent = $inputs['create_discount_percent'];
                $collaborator->collaboratorInformation->save();

                return redirect()->action('Frontend\CollaboratorController@editCollaboratorDownLine', ['id' => $collaborator->id])->with('messageSuccess', trans('theme.success'));
            }
            else
                return redirect()->action('Frontend\CollaboratorController@editCollaboratorDownLine', ['id' => $collaborator->id])->withErrors($validator)->withInput();
        }

        return view('frontend.collaborators.edit_collaborator_down_line', [
            'collaborator' => $collaborator,
        ]);
    }
}