<?php

namespace App\Http\Controllers\Frontend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Libraries\Helpers\Utility;
use App\Models\Widget;

class HomeController extends Controller
{
    public function home()
    {
        $widgetCodes = [
            Widget::HOME_SLIDER,
        ];

        $wgs = Widget::select('code', 'detail')->where('status', Utility::ACTIVE_DB)->whereIn('code', $widgetCodes)->get();

        $widgets = array();

        foreach($wgs as $wg)
            $widgets[$wg->code] = $wg;

        return view('frontend.homes.home', [
            'widgets' => $widgets,
        ]);
    }
}