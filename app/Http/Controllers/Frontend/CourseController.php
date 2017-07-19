<?php

namespace App\Http\Controllers\Frontend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Libraries\Helpers\Utility;
use App\Libraries\Widgets\GridView;
use App\Models\Course;
use App\Models\UserCourse;
use App\Models\Category;

class CourseController extends Controller
{
    public function detailCategory(Request $request, $id, $slug)
    {
        $category = Category::select('id', 'name', 'name_en', 'parent_id')
            ->where('id', $id)
            ->where('status', Utility::ACTIVE_DB)
            ->where('parent_status', Utility::ACTIVE_DB)
            ->where(function($query) use($slug) {
                $query->where('slug', $slug)->orWhere('slug_en', $slug);
            })->first();

        if(empty($category))
            return view('frontend.errors.404');

        $listCategories = Category::select('id', 'name', 'name_en', 'slug', 'slug_en')
            ->where('status', Utility::ACTIVE_DB)
            ->where('parent_status', Utility::ACTIVE_DB)
            ->where('parent_id', $category->parent_id)
            ->orderBy('order', 'desc')
            ->get();

        $courses = Course::select('course.*')
            ->join('category_course', 'course.id', '=', 'category_course.course_id')
            ->where('category_course.category_id', $category->id)
            ->where('course.status', Course::STATUS_PUBLISH_DB)
            ->where('course.category_status', Utility::ACTIVE_DB)
            ->orderBy('course.order', 'desc')
            ->paginate(GridView::ROWS_PER_PAGE);

        return view('frontend.courses.detail_category', [
            'category' => $category,
            'courses' => $courses,
            'listCategories' => $listCategories,
        ]);
    }

    public function detailCourse($id, $slug)
    {
        $course = Course::with(['user' => function($query) {
            $query->select('id');
        }, 'user.profile' => function($query) {
            $query->select('user_id', 'name');
        }, 'category' => function($query) {
            $query->select('id', 'name', 'name_en');
        }, 'promotionPrice' => function($query) {
            $query->select('course_id', 'status', 'price', 'start_time', 'end_time');
        }])
            ->select('id', 'user_id', 'name', 'name_en', 'price', 'description', 'description_en', 'point_price', 'video_length', 'level_id', 'short_description', 'short_description_en', 'image', 'item_count', 'bought_count', 'view_count', 'audio_length')
            ->where('id', $id)
            ->where('status', Course::STATUS_PUBLISH_DB)
            ->where('category_status', Utility::ACTIVE_DB)
            ->where(function($query) use($slug) {
                $query->where('slug', $slug)->orWhere('slug_en', $slug);
            })->first();

        if(empty($course))
            return view('frontend.errors.404');

        $bought = false;

        if(auth()->user())
        {
            $userCourse = UserCourse::where('user_id', auth()->user()->id)->where('course_id', $course->id)->first();

            if(!empty($userCourse))
                $bought = true;
        }

        return view('frontend.courses.detail_course', [
            'bought' => $bought,
            'course' => $course,
        ]);
    }
}