<?php

namespace App\Http\Controllers\Frontend;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Libraries\Helpers\Utility;
use App\Models\Course;
use App\Models\CourseItem;
use App\Models\UserCourse;
use App\Models\Category;
use App\Models\CourseReview;
use App\Models\CourseQuestion;

class CourseController extends Controller
{
    public function detailCategory($id, $slug, $sort = null)
    {
        $category = Category::select('id', 'name', 'name_en', 'parent_id', 'slug', 'slug_en')
            ->where('id', $id)
            ->where('status', Utility::ACTIVE_DB)
            ->where('parent_status', Utility::ACTIVE_DB)
            ->where(function($query) use($slug) {
                $query->where('slug', $slug)->orWhere('slug_en', $slug);
            })->first();

        if(empty($category))
            return view('frontend.errors.404');

        $parentCategories = array();

        $tempCategory = $category;
        while(!empty($tempCategory->parentCategory))
        {
            $parentCategories[] = $tempCategory->parentCategory;
            $tempCategory = $tempCategory->parentCategory;
        }

        $parentCategories = array_reverse($parentCategories);

        $listCategories = Category::select('id', 'name', 'name_en', 'slug', 'slug_en')
            ->where('status', Utility::ACTIVE_DB)
            ->where('parent_status', Utility::ACTIVE_DB)
            ->where('parent_id', $category->parent_id)
            ->orderBy('order', 'desc')
            ->get();

        $builder = Course::with(['user' => function($query) {
            $query->select('id');
        }, 'user.profile' => function($query) {
            $query->select('user_id', 'name');
        }, 'promotionPrice' => function($query) {
            $query->select('course_id', 'status', 'price', 'start_time', 'end_time');
        }])->select('course.id', 'course.user_id', 'course.name', 'course.name_en', 'course.price', 'course.image', 'course.slug', 'course.slug_en', 'course.bought_count', 'course.view_count')
            ->join('category_course', 'course.id', '=', 'category_course.course_id')
            ->where('category_course.category_id', $category->id)
            ->where('course.status', Course::STATUS_PUBLISH_DB)
            ->where('course.category_status', Utility::ACTIVE_DB)
            ->orderBy('course.published_at', 'desc');

        if($sort == 'highlight')
            $builder->where('course.highlight', Utility::ACTIVE_DB);
        else if($sort == 'promotion')
        {
            $time = date('Y-m-d H:i:s');

            $builder->join('promotion_price', 'course.id', '=', 'promotion_price.course_id')
                ->where('promotion_price.status', Utility::ACTIVE_DB)
                ->where('promotion_price.start_time', '<=', $time)
                ->where('promotion_price.end_time', '>=', $time);
        }
        else if($sort == 'free')
        {
            $builder->leftJoin('promotion_price', 'course.id', '=', 'promotion_price.course_id')
                ->where('course.price', 0)
                ->orWhere(function($query) {
                    $time = date('Y-m-d H:i:s');

                    $query->where('promotion_price.status', Utility::ACTIVE_DB)
                        ->where('promotion_price.start_time', '<=', $time)
                        ->where('promotion_price.end_time', '>=', $time)
                        ->where('promotion_price.price', 0);
                });
        }
        else
            $sort = null;

        $courses = $builder->paginate(Utility::FRONTEND_ROWS_PER_PAGE);

        return view('frontend.courses.detail_category', [
            'category' => $category,
            'courses' => $courses,
            'listCategories' => $listCategories,
            'parentCategories' => $parentCategories,
            'sort' => $sort,
        ]);
    }

    public function previewCourse(Request $request, $id, $slug)
    {
        if($request->ajax() == false)
            return view('frontend.errors.404');

        $course = Course::with(['user' => function($query) {
            $query->select('id');
        }, 'user.profile' => function($query) {
            $query->select('user_id', 'name');
        }, 'promotionPrice' => function($query) {
            $query->select('course_id', 'status', 'price', 'start_time', 'end_time');
        }])->select('id', 'user_id', 'name', 'name_en', 'price', 'video_length', 'image', 'item_count', 'audio_length')
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
            $userCourse = UserCourse::select('id')->where('user_id', auth()->user()->id)->where('course_id', $course->id)->first();

            if(!empty($userCourse))
                $bought = true;
        }

        return view('frontend.courses.partials.preview_course', [
            'bought' => $bought,
            'course' => $course,
        ]);
    }

    public function adminCourse($sort = null)
    {
        $listCategories = Category::select('id', 'name', 'name_en', 'slug', 'slug_en')
            ->where('status', Utility::ACTIVE_DB)
            ->where('parent_status', Utility::ACTIVE_DB)
            ->whereNull('parent_id')
            ->orderBy('order', 'desc')
            ->get();

        $builder = Course::with(['user' => function($query) {
            $query->select('id');
        }, 'user.profile' => function($query) {
            $query->select('user_id', 'name');
        }, 'promotionPrice' => function($query) {
            $query->select('course_id', 'status', 'price', 'start_time', 'end_time');
        }])->select('course.id', 'course.user_id', 'course.name', 'course.name_en', 'course.price', 'course.image', 'course.slug', 'course.slug_en', 'course.bought_count', 'course.view_count')
            ->where('course.status', Course::STATUS_PUBLISH_DB)
            ->where('course.category_status', Utility::ACTIVE_DB)
            ->orderBy('course.published_at', 'desc');

        if($sort == 'highlight')
        {
            $builder->where('course.highlight', Utility::ACTIVE_DB);
            $listTitle = trans('theme.highlight_course');
        }
        else if($sort == 'promotion')
        {
            $time = date('Y-m-d H:i:s');

            $builder->join('promotion_price', 'course.id', '=', 'promotion_price.course_id')
                ->where('promotion_price.status', Utility::ACTIVE_DB)
                ->where('promotion_price.start_time', '<=', $time)
                ->where('promotion_price.end_time', '>=', $time);

            $listTitle = trans('theme.discount_course');
        }
        else if($sort == 'free')
        {
            $builder->leftJoin('promotion_price', 'course.id', '=', 'promotion_price.course_id')
                ->where('course.price', 0)
                ->orWhere(function($query) {
                    $time = date('Y-m-d H:i:s');

                    $query->where('promotion_price.status', Utility::ACTIVE_DB)
                        ->where('promotion_price.start_time', '<=', $time)
                        ->where('promotion_price.end_time', '>=', $time)
                        ->where('promotion_price.price', 0);
                });

            $listTitle = trans('theme.free_course');
        }
        else
        {
            $sort = null;
            $listTitle = trans('theme.all_course');
        }

        $courses = $builder->paginate(Utility::FRONTEND_ROWS_PER_PAGE);

        return view('frontend.courses.admin_course', [
            'courses' => $courses,
            'listCategories' => $listCategories,
            'sort' => $sort,
            'listTitle' => $listTitle,
        ]);
    }

    public function detailCourse($id, $slug)
    {
        $course = Course::with(['user' => function($query) {
            $query->select('id');
        }, 'user.profile' => function($query) {
            $query->select('user_id', 'name');
        }, 'categoryCourses' => function($query) {
            $query->orderBy('level');
        }, 'categoryCourses.category' => function($query) {
            $query->select('id', 'name', 'name_en', 'slug', 'slug_en');
        }, 'promotionPrice' => function($query) {
            $query->select('course_id', 'status', 'price', 'start_time', 'end_time');
        }, 'courseItems' => function($query) {
            $query->select('id', 'course_id', 'name', 'name_en', 'number', 'video_length', 'audio_length');
        }])->select('id', 'user_id', 'name', 'name_en', 'slug', 'slug_en', 'price', 'description', 'description_en', 'point_price', 'video_length', 'level_id', 'short_description', 'short_description_en', 'image', 'item_count', 'bought_count', 'view_count', 'audio_length')
            ->where('id', $id)
            ->where('status', Course::STATUS_PUBLISH_DB)
            ->where('category_status', Utility::ACTIVE_DB)
            ->where(function($query) use($slug) {
                $query->where('slug', $slug)->orWhere('slug_en', $slug);
            })->first();

        if(empty($course))
            return view('frontend.errors.404');

        Utility::viewCount($course, 'view_count', Utility::VIEW_COURSE_COOKIE_NAME);

        $bought = false;
        $userCourse = null;

        if(auth()->user())
        {
            $userCourse = UserCourse::where('user_id', auth()->user()->id)->where('course_id', $course->id)->first();

            if(!empty($userCourse))
                $bought = true;
        }

        if($bought == false)
        {
            $redirect = Utility::setReferral($course);

            if(!empty($redirect))
                return redirect($redirect);
        }

        return view('frontend.courses.detail_course', [
            'bought' => $bought,
            'course' => $course,
            'userCourse' => $userCourse,
        ]);
    }

    public function detailCourseItem($id, $slug, $number)
    {
        $course = Course::with(['categoryCourses' => function($query) {
            $query->orderBy('level');
        }, 'categoryCourses.category' => function($query) {
            $query->select('id', 'name', 'name_en', 'slug', 'slug_en');
        }])->select('id', 'name', 'name_en', 'slug', 'slug_en', 'item_count', 'image', 'short_description', 'short_description_en')
            ->where('id', $id)
            ->where('status', Course::STATUS_PUBLISH_DB)
            ->where('category_status', Utility::ACTIVE_DB)
            ->where(function($query) use($slug) {
                $query->where('slug', $slug)->orWhere('slug_en', $slug);
            })->first();

        if(empty($course))
            return view('frontend.errors.404');

        $courseItem = CourseItem::select('id', 'name', 'name_en', 'type', 'content', 'content_en', 'number')
            ->where('course_id', $course->id)
            ->where('number', $number)
            ->first();

        if(empty($courseItem))
            return view('frontend.errors.404');

        $userCourse = UserCourse::where('user_id', auth()->user()->id)->where('course_id', $course->id)->first();

        if(empty($userCourse))
            return view('frontend.errors.404');

        if($courseItem->number == $userCourse->course_item_tracking + 1)
        {
            $userCourse->course_item_tracking += 1;

            if($courseItem->number == $course->item_count && $userCourse->finish == false)
            {
                $userCourse->finish = true;

                auth()->user()->studentInformation->finish_course_count += 1;
                auth()->user()->studentInformation->save();
            }

            $userCourse->save();
        }

        return view('frontend.courses.detail_course_item', [
            'course' => $course,
            'courseItem' => $courseItem,
            'userCourse' => $userCourse,
        ]);
    }

    public function getSource($token)
    {
        $filePath = Utility::getFilePathFromTemporarySourceToken(auth()->user(), $token);

        if($filePath)
            return response()->file($filePath);

        return '';
    }

    public function detailCourseReview(Request $request, $id, $slug)
    {
        if($request->ajax() == false)
            return view('frontend.errors.404');

        $courseReviews = CourseReview::with(['user' => function($query) {
            $query->select('id', 'avatar');
        }, 'user.profile' => function($query) {
            $query->select('user_id', 'name');
        }])->select('user_id', 'detail', 'created_at')
            ->where('status', CourseReview::STATUS_ACTIVE_DB)
            ->where('course_id', $id)
            ->orderBy('id', 'desc')
            ->paginate(Utility::FRONTEND_ROWS_PER_PAGE);

        return view('frontend.courses.partials.detail_course_review', ['courseReviews' => $courseReviews]);
    }

    public function reviewCourse(Request $request, $id, $slug)
    {
        $course = Course::select('id')->find($id);

        if(empty($course))
            return '';

        $userCourse = UserCourse::where('user_id', auth()->user()->id)->where('course_id', $id)->first();

        if(empty($userCourse))
            return '';

        $inputs = $request->all();

        $validator = Validator::make($inputs, [
            'detail' => 'required|string|max:1000',
        ]);

        if($validator->passes())
        {
            $review = new CourseReview();
            $review->user_id = auth()->user()->id;
            $review->course_id = $course->id;
            $review->detail = $inputs['detail'];
            $review->created_at = date('Y-m-d H:i:s');
            $review->status = CourseReview::STATUS_PENDING_DB;
            $review->save();

            return 'Success';
        }

        return '';
    }

    public static function getNewCourses()
    {
        if(request()->hasCookie(Utility::VISIT_START_TIME_COOKIE_NAME))
        {
            $newCourses = Course::select('id', 'name', 'name_en', 'image', 'slug', 'slug_en', 'short_description', 'short_description_en')
                ->where('status', Course::STATUS_PUBLISH_DB)
                ->where('category_status', Utility::ACTIVE_DB)
                ->where('published_at', '>=', date('Y-m-d H:i:s', request()->cookie(Utility::VISIT_START_TIME_COOKIE_NAME)))
                ->orderBy('published_at', 'desc')
                ->get();

            return $newCourses;
        }

        return array();
    }

    public function newCourseAndNews(Request $request)
    {
        if($request->ajax() == false)
            return view('frontend.errors.404');

        if(request()->hasCookie(Utility::VISIT_START_TIME_COOKIE_NAME))
        {
            $newCourses = self::getNewCourses();
            $newNews = NewsController::getNewNews();

            $newHtml = [
                'courses' => view('frontend.courses.partials.new_course', [
                    'newCourses' => $newCourses,
                ])->render(),
                'news' => view('frontend.news.partials.new_news', [
                    'newNews' => $newNews,
                ])->render(),
            ];

            return json_encode($newHtml);
        }

        return '';
    }

    public function searchCourse(Request $request)
    {
        $inputs = $request->all();

        $validator = Validator::make($inputs, [
            'k' => 'required|min:2|max:100',
        ]);

        if($validator->passes())
        {
            $keywords = explode(' ', Utility::removeWhitespace($inputs['k']));
            $countKeyword = count($keywords);
            $searchKeywords = array();
            for($i = 0;$i < $countKeyword;$i ++)
                $searchKeywords[] = implode(' ', array_slice($keywords, $i, 2));

            $courses = Course::with(['user' => function($query) {
                $query->select('id');
            }, 'user.profile' => function($query) {
                $query->select('user_id', 'name');
            }, 'promotionPrice' => function($query) {
                $query->select('course_id', 'status', 'price', 'start_time', 'end_time');
            }])->select('course.id', 'course.user_id', 'course.name', 'course.name_en', 'course.price', 'course.image', 'course.slug', 'course.slug_en', 'course.bought_count', 'course.view_count')
                ->join('category_course', 'course.id', '=', 'category_course.course_id')
                ->join('category', 'category_course.category_id', '=', 'category.id')
                ->where('course.status', Course::STATUS_PUBLISH_DB)
                ->where('course.category_status', Utility::ACTIVE_DB)
                ->where(function($query) use($searchKeywords) {

                    $query->where('course.name', 'like', '%' . $searchKeywords[0] . '%')->orWhere('course.name_en', 'like', '%' . $searchKeywords[0] . '%')
                        ->orWhere('category.name', 'like', '%' . $searchKeywords[0] . '%')->orWhere('category.name_en', 'like', '%' . $searchKeywords[0] . '%');

                    $countKeyword = count($searchKeywords);
                    if($countKeyword > 1)
                    {
                        for($i = 1;$i < $countKeyword;$i ++)
                        {
                            $query->orWhere('course.name', 'like', '%' . $searchKeywords[$i] . '%')->orWhere('course.name_en', 'like', '%' . $searchKeywords[$i] . '%')
                                ->orWhere('category.name', 'like', '%' . $searchKeywords[$i] . '%')->orWhere('category.name_en', 'like', '%' . $searchKeywords[$i] . '%');
                        }
                    }

                })
                ->orderBy('course.published_at', 'desc')
                ->paginate(Utility::FRONTEND_ROWS_PER_PAGE);
        }
        else
            $courses = null;

        return view('frontend.courses.search_course', [
            'courses' => $courses,
            'keyword' => isset($inputs['k']) ? $inputs['k'] : '',
        ]);
    }

    public function saveCourseItemNote(Request $request, $id)
    {
        $courseItem = CourseItem::find($id);

        if(empty($courseItem))
            return '';

        $userCourse = UserCourse::where('user_id', auth()->user()->id)->where('course_id', $courseItem->course_id)->first();

        if(empty($userCourse))
            return '';

        $inputs = $request->all();

        $validator = Validator::make($inputs, [
            'note' => 'nullable|string|max:1000',
        ]);

        if($validator->passes())
        {
            $studentNotes = array();
            if(!empty($userCourse->student_note))
                $studentNotes = json_decode($userCourse->student_note, true);

            $studentNotes[$courseItem->number] = $inputs['note'];

            $userCourse->student_note = json_encode($studentNotes);
            $userCourse->save();

            return 'Success';
        }

        return '';
    }

    public function detailCourseQuestion(Request $request, $id, $slug)
    {
        if($request->ajax() == false)
            return view('frontend.errors.404');

        $user = auth()->user();

        $courseQuestions = CourseQuestion::with(['user' => function($query) {
            $query->select('id', 'avatar');
        }, 'course' => function($query) {
            $query->select('id', 'user_id');
        }, 'course.user' => function($query) {
            $query->select('id', 'avatar');
        }, 'course.user.profile' => function($query) {
            $query->select('user_id', 'name');
        }])->select('user_id', 'course_id', 'question', 'answer', 'created_at')
            ->where('status', CourseQuestion::STATUS_ACTIVE_DB)
            ->where('course_id', $id)
            ->where('user_id', $user->id)
            ->orderBy('id', 'desc')
            ->paginate(Utility::FRONTEND_ROWS_PER_PAGE);

        return view('frontend.courses.partials.detail_course_question', ['courseQuestions' => $courseQuestions]);
    }

    public function questionCourse(Request $request, $id, $slug)
    {
        $course = Course::select('id')->find($id);

        if(empty($course))
            return '';

        $userCourse = UserCourse::where('user_id', auth()->user()->id)->where('course_id', $id)->first();

        if(empty($userCourse))
            return '';

        $inputs = $request->all();

        $validator = Validator::make($inputs, [
            'question' => 'required|string|max:1000',
        ]);

        if($validator->passes())
        {
            $question = new CourseQuestion();
            $question->user_id = auth()->user()->id;
            $question->course_id = $course->id;
            $question->question = $inputs['question'];
            $question->created_at = date('Y-m-d H:i:s');
            $question->status = CourseReview::STATUS_PENDING_DB;
            $question->save();

            return 'Success';
        }

        return '';
    }
}