<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    const WEB_TITLE = 'web_title';
    const WEB_DESCRIPTION = 'web_description';

    const EXCHANGE_USD_RATE = 'exchange_usd_rate';
    const EXCHANGE_POINT_RATE = 'exchange_point_rate';

    const COLLABORATOR_SILVER = 'collaborator_silver';
    const COLLABORATOR_GOLD = 'collaborator_gold';
    const COLLABORATOR_DIAMOND = 'collaborator_diamond';
    const COLLABORATOR_MANAGER = 'collaborator_manager';

    const TYPE_STRING_DB = 0;
    const TYPE_INT_DB = 1;
    const TYPE_JSON_DB = 2;

    const CATEGORY_GENERAL_DB = 0;
    const CATEGORY_COLLABORATOR_DB = 1;

    protected static $settings = null;

    protected $table = 'setting';

    public $timestamps = false;
    
    public static function initCoreSettings()
    {
        $coreSettings = [
            [self::WEB_TITLE, 'Tiêu Đề Web', self::TYPE_STRING_DB, 'caydenthan', self::CATEGORY_GENERAL_DB],
            [self::WEB_DESCRIPTION, 'Mô Tả Web', self::TYPE_STRING_DB, 'caydenthan', self::CATEGORY_GENERAL_DB],

            [self::EXCHANGE_USD_RATE, 'Tỉ Lệ Quy Đổi USD', self::TYPE_INT_DB, 22000, self::CATEGORY_GENERAL_DB],
            [self::EXCHANGE_POINT_RATE, 'Tỉ Lệ Quy Đổi Điểm', self::TYPE_INT_DB, 1000, self::CATEGORY_GENERAL_DB],

            [
                self::COLLABORATOR_SILVER,
                'Cộng Tác Viên Bạc',
                self::TYPE_JSON_DB,
                json_encode([
                    Collaborator::DISCOUNT_ATTRIBUTE => 10,
                    Collaborator::COMMISSION_ATTRIBUTE => 10,
                    Collaborator::REVENUE_ATTRIBUTE => 20000000,
                ]),
                self::CATEGORY_COLLABORATOR_DB,
            ],
            [
                self::COLLABORATOR_GOLD,
                'Cộng Tác Viên Vàng',
                self::TYPE_JSON_DB,
                json_encode([
                    Collaborator::DISCOUNT_ATTRIBUTE => 15,
                    Collaborator::COMMISSION_ATTRIBUTE => 15,
                    Collaborator::REVENUE_ATTRIBUTE => 50000000,
                ]),
                self::CATEGORY_COLLABORATOR_DB,
            ],
            [
                self::COLLABORATOR_DIAMOND,
                'Cộng Tác Viên Kim Cương',
                self::TYPE_JSON_DB,
                json_encode([
                    Collaborator::DISCOUNT_ATTRIBUTE => 20,
                    Collaborator::COMMISSION_ATTRIBUTE => 20,
                    Collaborator::RERANK_TIME_ATTRIBUTE => 12,
                ]),
                self::CATEGORY_COLLABORATOR_DB,
            ],
            [
                self::COLLABORATOR_MANAGER,
                'Quản Lý Kinh Doanh',
                self::TYPE_JSON_DB,
                json_encode([
                    Collaborator::DISCOUNT_ATTRIBUTE => 30,
                    Collaborator::COMMISSION_ATTRIBUTE => 20,
                    Collaborator::COMMISSION_DOWNLINE_ATTRIBUTE => 5,
                    Collaborator::DISCOUNT_DOWNLINE_SET_ATTRIBUTE => 20,
                    Collaborator::COMMISSION_DOWNLINE_SET_ATTRIBUTE => 15,
                ]),
                self::CATEGORY_COLLABORATOR_DB,
            ],
        ];

        foreach($coreSettings as $coreSetting)
        {
            $setting = new Setting();
            $setting->code = $coreSetting[0];
            $setting->name = $coreSetting[1];
            $setting->type = $coreSetting[2];
            $setting->value = $coreSetting[3];
            $setting->category = $coreSetting[4];
            $setting->save();
        }
    }

    public static function getSettings($category = self::CATEGORY_GENERAL_DB, $code = null)
    {
        if(self::$settings == null || !isset(self::$settings[$category]))
        {
            $settings = Setting::where('category', $category)->get();

            foreach($settings as $setting)
                self::$settings[$category][$setting->code] = $setting;
        }

        if($code != null)
        {
            if(isset(self::$settings[$category][$code]))
            {
                if(self::$settings[$category][$code]->type == self::TYPE_JSON_DB)
                    return json_decode(self::$settings[$category][$code]->value, true);
                else
                    return self::$settings[$category][$code]->value;
            }
        }

        return self::$settings[$category];
    }
}