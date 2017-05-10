<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Setting;

class SettingController extends Controller
{
    public function adminSetting()
    {
        $settings = Setting::all();

        return view('backend.settings.admin_setting', [
            'settings' => $settings,
        ]);
    }

    public function editSetting(Request $request)
    {

    }
}