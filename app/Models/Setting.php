<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    const WEB_TITLE = 'WEB_TITLE';
    const WEB_DESCRIPTION = 'WEB_DESCRIPTION';

    const TYPE_STRING_DB = 0;
    const TYPE_INT_DB = 1;
    const TYPE_JSON_DB = 2;

    protected static $settings = null;

    protected $table = 'setting';

    public $timestamps = false;
    
    public static function initCoreSettings()
    {
        $coreSettings = [
            [self::WEB_TITLE, 'Web Title', self::TYPE_STRING_DB, 'caydenthan'],
            [self::WEB_DESCRIPTION, 'Web Description', self::TYPE_STRING_DB, 'caydenthan'],
        ];

        foreach($coreSettings as $coreSetting)
        {
            $setting = new Setting();
            $setting->code = $coreSetting[0];
            $setting->name = $coreSetting[1];
            $setting->type = $coreSetting[2];
            $setting->value = $coreSetting[3];
            $setting->save();
        }
    }

    public static function getSettings($code = null)
    {
        if(self::$settings == null)
        {
            $settings = Setting::all()->toArray();

            foreach($settings as $setting)
                self::$settings[$setting['code']] = $setting;
        }

        if($code != null)
        {
            if(isset(self::$settings[$code]))
            {
                if(self::$settings[$code]['type'] == self::TYPE_JSON_DB)
                    return json_decode(self::$settings[$code]['value'], true);
                else
                    return self::$settings[$code]['value'];
            }
        }

        return self::$settings;
    }
}