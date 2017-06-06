<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Setting;

class SettingController extends Controller
{
    public function adminSetting(Request $request)
    {
        $settings = Setting::getSettings();

        if($request->isMethod('post'))
        {
            $inputs = $request->all();

            $settings[Setting::WEB_TITLE]->value = $inputs['web_title'];
            $settings[Setting::WEB_TITLE]->save();
            $settings[Setting::WEB_DESCRIPTION]->value = $inputs['web_description'];
            $settings[Setting::WEB_DESCRIPTION]->save();

            return redirect()->action('Backend\SettingController@adminSetting')->with('messageSuccess', 'Thành Công');
        }

        return view('backend.settings.admin_setting', [
            'settings' => $settings,
        ]);
    }
}