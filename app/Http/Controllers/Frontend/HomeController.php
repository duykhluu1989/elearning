<?php

namespace App\Http\Controllers\Frontend;

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

        $courseIds = array();

        $groupCourses = [
            Widget::GROUP_FREE_COURSE => array(),
            Widget::GROUP_HIGHLIGHT_COURSE => array(),
            Widget::GROUP_DISCOUNT_COURSE => array(),
        ];

        $courseGroupCodeByIds = array();

        foreach($groupCodes as $groupCode)
        {
            if(isset($widgets[$groupCode]))
            {
                if(!empty($widgets[$groupCode]->detail))
                {
                    $courseItems = json_decode($widgets[$groupCode]->detail, true);

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

        $courses = Course::select('id', 'name', 'name_en', 'price', 'image')
            ->where('status', Course::STATUS_PUBLISH_DB)->whereIn('id', $courseIds)->get();

        foreach($courses as $course)
            $groupCourses[$courseGroupCodeByIds[$course->id]][$course->id] = $course;

        return $groupCourses;
    }
}