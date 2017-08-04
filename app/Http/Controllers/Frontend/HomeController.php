<?php

namespace App\Http\Controllers\Frontend;

use Illuminate\Support\Facades\Cookie;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Libraries\Helpers\Utility;
use App\Models\Widget;
use App\Models\Course;
use App\Models\Category;
use App\Models\CaseAdvice;
use App\Models\Certificate;
use App\Models\NewsCategory;
use App\Models\Expert;
use App\Models\ExpertEvent;

class HomeController extends Controller
{
    public function home()
    {
        $widgetCodes = [
            Widget::HOME_SLIDER,
            Widget::GROUP_FREE_COURSE,
            Widget::GROUP_HIGHLIGHT_COURSE,
            Widget::GROUP_DISCOUNT_COURSE,
            Widget::ADVERTISE_HORIZONTAL_TOP,
            Widget::ADVERTISE_VERTICAL_LEFT,
            Widget::ADVERTISE_VERTICAL_RIGHT,
            Widget::GROUP_STAFF_EXPERT,
            Widget::GROUP_STAFF_STUDENT,
            Widget::GROUP_STAFF_TEACHER,
        ];

        for($i = 1;$i <= 3;$i ++)
            $widgetCodes[] = Widget::GROUP_CUSTOM_COURSE . '_' . $i;

        $wgs = Widget::select('code', 'detail')->where('status', Utility::ACTIVE_DB)->whereIn('code', $widgetCodes)->get();

        $widgets = array();

        foreach($wgs as $wg)
            $widgets[$wg->code] = $wg;

        $groupCourses = $this->prepareGroupCourseData($widgets);

        $rootCategories = Category::select('id', 'name', 'name_en', 'slug', 'slug_en')
            ->where('status', Utility::ACTIVE_DB)
            ->where('parent_status', Utility::ACTIVE_DB)
            ->whereNull('parent_id')
            ->orderBy('order', 'desc')
            ->get();

        $caseAdviceEconomies = CaseAdvice::select('id', 'name', 'name_en', 'slug', 'slug_en')
            ->where('type', CaseAdvice::TYPE_ECONOMY_DB)
            ->where('status', Utility::ACTIVE_DB)
            ->orderBy('order', 'desc')
            ->limit(Utility::FRONTEND_HOME_ITEM_LIMIT)
            ->get();

        $caseAdviceLaws = CaseAdvice::select('id', 'name', 'name_en', 'slug', 'slug_en')
            ->where('type', CaseAdvice::TYPE_LAW_DB)
            ->where('status', Utility::ACTIVE_DB)
            ->orderBy('order', 'desc')
            ->limit(Utility::FRONTEND_HOME_ITEM_LIMIT)
            ->get();

        $certificates = Certificate::select('id', 'name', 'name_en')
            ->where('status', Utility::ACTIVE_DB)
            ->orderBy('order', 'desc')
            ->limit(Utility::FRONTEND_HOME_ITEM_LIMIT)
            ->get();

        $newsCategories = NewsCategory::select('id', 'name', 'name_en', 'slug', 'slug_en', 'image')
            ->where('status', Utility::ACTIVE_DB)
            ->orderBy('order', 'desc')
            ->get();

        $onlineExperts = Expert::with(['user' => function($query) {
            $query->select('id', 'avatar');
        }, 'user.profile' => function($query) {
            $query->select('user_id', 'name');
        }])->where('online', Utility::ACTIVE_DB)
            ->get();

        $oldExpertEvents = ExpertEvent::orderBy('created_at', 'desc')
            ->limit(Utility::FRONTEND_HOME_ITEM_LIMIT)
            ->get();

        return view('frontend.homes.home', [
            'widgets' => $widgets,
            'groupCourses' => $groupCourses,
            'rootCategories' => $rootCategories,
            'caseAdviceEconomies' => $caseAdviceEconomies,
            'caseAdviceLaws' => $caseAdviceLaws,
            'certificates' => $certificates,
            'newsCategories' => $newsCategories,
            'onlineExperts' => $onlineExperts,
            'oldExpertEvents' => $oldExpertEvents,
        ]);
    }

    protected function prepareGroupCourseData($widgets)
    {
        $groupCodes = [
            Widget::GROUP_FREE_COURSE,
            Widget::GROUP_HIGHLIGHT_COURSE,
            Widget::GROUP_DISCOUNT_COURSE,
        ];

        for($i = 1;$i <= 3;$i ++)
            $groupCodes[] = Widget::GROUP_CUSTOM_COURSE . '_' . $i;

        $courseIds = array();

        $groupCourses = array();

        foreach($groupCodes as $groupCode)
        {
            $groupCourses[$groupCode] = array();

            if(isset($widgets[$groupCode]))
            {
                if(!empty($widgets[$groupCode]->detail))
                {
                    $courseItems = json_decode($widgets[$groupCode]->detail, true);

                    unset($courseItems['custom_detail']);

                    if(count($courseItems) > 0)
                    {
                        foreach($courseItems as $courseItem)
                        {
                            $courseIds[] = $courseItem['course_id'];
                            $groupCourses[$groupCode][$courseItem['course_id']] = '';
                        }
                    }
                }
            }
        }

        $courses = Course::select('id', 'name', 'name_en', 'price', 'image', 'slug', 'slug_en', 'category_id')
            ->with(['category' => function($query) {
                $query->select('id', 'name', 'name_en');
            }, 'promotionPrice' => function($query) {
                $query->select('course_id', 'status', 'price', 'start_time', 'end_time');
            }])->where('status', Course::STATUS_PUBLISH_DB)->where('category_status', Utility::ACTIVE_DB)
            ->whereIn('id', $courseIds)->get();

        foreach($courses as $course)
        {
            foreach($groupCourses as $code => $preSetCourses)
            {
                foreach($preSetCourses as $courseId => $preSetCourse)
                {
                    if($courseId == $course->id)
                    {
                        $groupCourses[$code][$courseId] = $course;

                        break;
                    }
                }
            }
        }

        foreach($groupCourses as $code => $setCourses)
        {
            foreach($setCourses as $courseId => $setCourse)
            {
                if(empty($setCourse))
                    unset($groupCourses[$code][$courseId]);
            }
        }

        return $groupCourses;
    }

    public function language(Request $request, $locale)
    {
        $referer = $request->headers->get('referer');

        if(empty($referer) || strpos($referer, '/language'))
            $referer = '/';

        if($locale == 'vi')
            return redirect($referer)->withCookie(Cookie::forget(Utility::LANGUAGE_COOKIE_NAME));
        else
            return redirect($referer)->withCookie(Utility::LANGUAGE_COOKIE_NAME, 'en', Utility::MINUTE_ONE_MONTH);
    }

    public function refreshCsrfToken()
    {
        return csrf_token();
    }
}