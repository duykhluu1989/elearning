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

            $inputs[Setting::EXCHANGE_USD_RATE] = implode('', explode('.', $inputs[Setting::EXCHANGE_USD_RATE]));
            $inputs[Setting::EXCHANGE_POINT_RATE] = implode('', explode('.', $inputs[Setting::EXCHANGE_POINT_RATE]));

            $validator = Validator::make($inputs, [
                Setting::EXCHANGE_USD_RATE => 'required|integer|min:1',
                Setting::EXCHANGE_POINT_RATE => 'required|integer|min:1',
            ]);

            if($validator->passes())
            {
                try
                {
                    DB::beginTransaction();

                    foreach($inputs as $key => $value)
                    {
                        if(isset($settings[$key]))
                        {
                            $settings[$key]->value = $value;
                            $settings[$key]->save();
                        }
                    }

                    DB::commit();

                    return redirect()->action('Backend\SettingController@adminSetting')->with('messageSuccess', 'Thành Công');
                }
                catch(\Exception $e)
                {
                    DB::rollBack();

                    return redirect()->action('Backend\SettingController@adminSetting')->withInput()->with('messageError', $e->getMessage());
                }
            }
            else
                return redirect()->action('Backend\SettingController@adminSetting')->withErrors($validator)->withInput();
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

            $inputs[Setting::COLLABORATOR_SILVER][Collaborator::REVENUE_ATTRIBUTE] = implode('', explode('.', $inputs[Setting::COLLABORATOR_SILVER][Collaborator::REVENUE_ATTRIBUTE]));
            $inputs[Setting::COLLABORATOR_GOLD][Collaborator::REVENUE_ATTRIBUTE] = implode('', explode('.', $inputs[Setting::COLLABORATOR_GOLD][Collaborator::REVENUE_ATTRIBUTE]));

            $validator = Validator::make($inputs, [
                Setting::COLLABORATOR_SILVER . '.' . Collaborator::DISCOUNT_ATTRIBUTE => 'required|integer|min:1|max:99',
                Setting::COLLABORATOR_SILVER . '.' . Collaborator::COMMISSION_ATTRIBUTE => 'required|integer|min:1|max:99',
                Setting::COLLABORATOR_SILVER . '.' . Collaborator::REVENUE_ATTRIBUTE => 'required|integer|min:1',
                Setting::COLLABORATOR_GOLD . '.' . Collaborator::DISCOUNT_ATTRIBUTE => 'required|integer|min:1|max:99',
                Setting::COLLABORATOR_GOLD . '.' . Collaborator::COMMISSION_ATTRIBUTE => 'required|integer|min:1|max:99',
                Setting::COLLABORATOR_GOLD . '.' . Collaborator::REVENUE_ATTRIBUTE => 'required|integer|min:1',
                Setting::COLLABORATOR_DIAMOND . '.' . Collaborator::DISCOUNT_ATTRIBUTE => 'required|integer|min:1|max:99',
                Setting::COLLABORATOR_DIAMOND . '.' . Collaborator::COMMISSION_ATTRIBUTE => 'required|integer|min:1|max:99',
                Setting::COLLABORATOR_DIAMOND . '.' . Collaborator::RERANK_TIME_ATTRIBUTE => 'required|integer|min:1',
                Setting::COLLABORATOR_MANAGER . '.' . Collaborator::DISCOUNT_ATTRIBUTE => 'required|integer|min:1|max:99',
                Setting::COLLABORATOR_MANAGER . '.' . Collaborator::COMMISSION_ATTRIBUTE => 'required|integer|min:1|max:99',
                Setting::COLLABORATOR_MANAGER . '.' . Collaborator::COMMISSION_DOWNLINE_ATTRIBUTE => 'required|integer|min:1|max:99',
                Setting::COLLABORATOR_MANAGER . '.' . Collaborator::DISCOUNT_DOWNLINE_SET_ATTRIBUTE => 'required|integer|min:1|max:99',
                Setting::COLLABORATOR_MANAGER . '.' . Collaborator::COMMISSION_DOWNLINE_SET_ATTRIBUTE => 'required|integer|min:1|max:99',
            ]);

            if($validator->passes())
            {
                try
                {
                    DB::beginTransaction();

                    foreach($inputs as $key => $value)
                    {
                        if(isset($settings[$key]))
                        {
                            $settings[$key]->value = json_encode($value);
                            $settings[$key]->save();
                        }
                    }

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

    public function getSettingCollaboratorValue(Request $request)
    {
        $id = $request->input('id');

        $setting = Setting::find($id);

        return $setting->value;
    }

    public function adminSettingSocial(Request $request)
    {
        $settings = Setting::getSettings(Setting::CATEGORY_SOCIAL_DB);

        if($request->isMethod('post'))
        {
            $inputs = $request->all();

            try
            {
                DB::beginTransaction();

                foreach($inputs as $key => $value)
                {
                    if(isset($settings[$key]))
                    {
                        $settings[$key]->value = $value;
                        $settings[$key]->save();
                    }
                }

                DB::commit();

                return redirect()->action('Backend\SettingController@adminSettingSocial')->with('messageSuccess', 'Thành Công');
            }
            catch(\Exception $e)
            {
                DB::rollBack();

                return redirect()->action('Backend\SettingController@adminSettingSocial')->withInput()->with('messageError', $e->getMessage());
            }
        }

        return view('backend.settings.admin_setting_social', [
            'settings' => $settings,
        ]);
    }
}