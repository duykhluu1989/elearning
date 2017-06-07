<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Setting;
use App\Models\Collaborator;

class SettingController extends Controller
{
    public function adminSetting(Request $request)
    {
        $settings = Setting::getSettings(Setting::CATEGORY_GENERAL_DB);

        if($request->isMethod('post'))
        {
            $inputs = $request->all();

            try
            {
                DB::beginTransaction();

                $settings[Setting::WEB_TITLE]->value = $inputs['web_title'];
                $settings[Setting::WEB_TITLE]->save();

                $settings[Setting::WEB_DESCRIPTION]->value = $inputs['web_description'];
                $settings[Setting::WEB_DESCRIPTION]->save();

                DB::commit();

                return redirect()->action('Backend\SettingController@adminSetting')->with('messageSuccess', 'Thành Công');
            }
            catch(\Exception $e)
            {
                DB::rollBack();

                return redirect()->action('Backend\SettingController@adminSetting')->withInput()->with('messageError', $e->getMessage());
            }
        }

        return view('backend.settings.admin_setting', [
            'settings' => $settings,
        ]);
    }

    public function adminSettingCollaborator(Request $request)
    {
        $settings = Setting::getSettings(Setting::CATEGORY_COLLABORATOR_DB);

        if($request->isMethod('post'))
        {
            $inputs = $request->all();

            $inputs['collaborator_silver'][Collaborator::REVENUE_ATTRIBUTE] = implode('', explode('.', $inputs['collaborator_silver'][Collaborator::REVENUE_ATTRIBUTE]));
            $inputs['collaborator_gold'][Collaborator::REVENUE_ATTRIBUTE] = implode('', explode('.', $inputs['collaborator_gold'][Collaborator::REVENUE_ATTRIBUTE]));

            $validator = Validator::make($inputs, [
                'collaborator_silver.' . Collaborator::DISCOUNT_ATTRIBUTE => 'required|integer|min:1|max:99',
                'collaborator_silver.' . Collaborator::COMMISSION_ATTRIBUTE => 'required|integer|min:1|max:99',
                'collaborator_silver.' . Collaborator::REVENUE_ATTRIBUTE => 'required|integer|min:1',
                'collaborator_gold.' . Collaborator::DISCOUNT_ATTRIBUTE => 'required|integer|min:1|max:99',
                'collaborator_gold.' . Collaborator::COMMISSION_ATTRIBUTE => 'required|integer|min:1|max:99',
                'collaborator_gold.' . Collaborator::REVENUE_ATTRIBUTE => 'required|integer|min:1',
                'collaborator_diamond.' . Collaborator::DISCOUNT_ATTRIBUTE => 'required|integer|min:1|max:99',
                'collaborator_diamond.' . Collaborator::COMMISSION_ATTRIBUTE => 'required|integer|min:1|max:99',
                'collaborator_diamond.' . Collaborator::RERANK_TIME_ATTRIBUTE => 'required|integer|min:1',
                'collaborator_manager.' . Collaborator::DISCOUNT_ATTRIBUTE => 'required|integer|min:1|max:99',
                'collaborator_manager.' . Collaborator::COMMISSION_ATTRIBUTE => 'required|integer|min:1|max:99',
                'collaborator_manager.' . Collaborator::COMMISSION_DOWNLINE_ATTRIBUTE => 'required|integer|min:1|max:99',
                'collaborator_manager.' . Collaborator::DISCOUNT_DOWNLINE_SET_ATTRIBUTE => 'required|integer|min:1|max:99',
                'collaborator_manager.' . Collaborator::COMMISSION_DOWNLINE_SET_ATTRIBUTE => 'required|integer|min:1|max:99',
            ]);

            if($validator->passes())
            {
                try
                {
                    DB::beginTransaction();

                    $settings[Setting::COLLABORATOR_SILVER]->value = json_encode($inputs['collaborator_silver']);
                    $settings[Setting::COLLABORATOR_SILVER]->save();

                    $settings[Setting::COLLABORATOR_GOLD]->value = json_encode($inputs['collaborator_gold']);
                    $settings[Setting::COLLABORATOR_GOLD]->save();

                    $settings[Setting::COLLABORATOR_DIAMOND]->value = json_encode($inputs['collaborator_diamond']);
                    $settings[Setting::COLLABORATOR_DIAMOND]->save();

                    $settings[Setting::COLLABORATOR_MANAGER]->value = json_encode($inputs['collaborator_manager']);
                    $settings[Setting::COLLABORATOR_MANAGER]->save();

                    DB::commit();

                    return redirect()->action('Backend\SettingController@adminSettingCollaborator')->with('messageSuccess', 'Thành Công');
                }
                catch(\Exception $e)
                {
                    DB::rollBack();

                    return redirect()->action('Backend\SettingController@adminSettingCollaborator')->withInput()->with('messageError', $e->getMessage());
                }
            }
            else
                return redirect()->action('Backend\SettingController@adminSettingCollaborator')->withErrors($validator)->withInput();
        }

        return view('backend.settings.admin_setting_collaborator', [
            'settings' => $settings,
        ]);
    }
}