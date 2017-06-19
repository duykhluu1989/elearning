<?php

namespace App\Http\Controllers\Frontend;

use Illuminate\Support\Facades\Cookie;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Libraries\Helpers\Utility;
use App\Models\Widget;
use App\Models\Course;

class HomeController extends Controller
{
    public function home()
    {
        $widgetCodes = [
            Widget::HOME_SLIDER,
            Widget::GROUP_FREE_COURSE,
            Widget::GROUP_HIGHLIGHT_COURSE,
            Widget::GROUP_DISCOUNT_COURSE,
        ];

        for($i = 1;$i <= 3;$i ++)
            $widgetCodes[] = Widget::GROUP_CUSTOM_COURSE . '_' . $i;

        $wgs = Widget::select('code', 'detail')->where('status', Utility::ACTIVE_DB)->whereIn('code', $widgetCodes)->get();

        $widgets = array();

        foreach($wgs as $wg)
            $widgets[$wg->code] = $wg;

        $groupCourses = $this->prepareGroupCourseData($widgets);

        return view('frontend.homes.home', [
            'widgets' => $widgets,
            'groupCourses' => $groupCourses,
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

        $courseGroupCodeByIds = array();

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
                            $courseGroupCodeByIds[$courseItem['course_id']] = $groupCode;
                        }
                    }
                }
            }
        }

        $courses = Course::select('id', 'name', 'name_en', 'price', 'image', 'slug', 'slug_en')
            ->where('status', Course::STATUS_PUBLISH_DB)->whereIn('id', $courseIds)->get();

        foreach($courses as $course)
            $groupCourses[$courseGroupCodeByIds[$course->id]][$course->id] = $course;

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
}