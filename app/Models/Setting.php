<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    const WEB_TITLE = 'web_title';
    const WEB_DESCRIPTION = 'web_description';
    const WEB_KEYWORD = 'web_keyword';
    const WEB_LOGO = 'web_logo';
    const HOT_LINE = 'hot_line';
    const CONTACT_EMAIL = 'contact_email';
    const WEB_VISITOR_COUNT = 'web_visitor_count';

    const EXCHANGE_USD_RATE = 'exchange_usd_rate';
    const EXCHANGE_POINT_RATE = 'exchange_point_rate';

    const COLLABORATOR_SILVER = 'collaborator_silver';
    const COLLABORATOR_GOLD = 'collaborator_gold';
    const COLLABORATOR_DIAMOND = 'collaborator_diamond';
    const COLLABORATOR_MANAGER = 'collaborator_manager';

    const FACEBOOK_APP_ID = 'facebook_app_id';
    const FACEBOOK_APP_SECRET = 'facebook_app_secret';
    const FACEBOOK_GRAPH_VERSION = 'facebook_graph_version';
    const FACEBOOK_PAGE_URL = 'facebook_page_url';

    const TYPE_STRING_DB = 0;
    const TYPE_INT_DB = 1;
    const TYPE_JSON_DB = 2;
    const TYPE_IMAGE_DB = 3;

    const CATEGORY_GENERAL_DB = 0;
    const CATEGORY_COLLABORATOR_DB = 1;
    const CATEGORY_SOCIAL_DB = 2;

    protected static $settings;

    protected $table = 'setting';

    public $timestamps = false;
    
    public static function initCoreSettings()
    {
        $coreSettings = [
            [self::WEB_TITLE, 'Tiêu Đề Website', self::TYPE_STRING_DB, 'caydenthan', self::CATEGORY_GENERAL_DB],
            [self::WEB_DESCRIPTION, 'Mô Tả Website', self::TYPE_STRING_DB, 'caydenthan', self::CATEGORY_GENERAL_DB],
            [self::WEB_KEYWORD, 'Từ Khóa', self::TYPE_STRING_DB, 'caydenthan', self::CATEGORY_GENERAL_DB],
            [self::WEB_LOGO, 'Logo', self::TYPE_IMAGE_DB, '', self::CATEGORY_GENERAL_DB],
            [self::HOT_LINE, 'Hot Line', self::TYPE_STRING_DB, '', self::CATEGORY_GENERAL_DB],
            [self::CONTACT_EMAIL, 'Email Liên Hệ', self::TYPE_STRING_DB, 'admin@caydenthan.info', self::CATEGORY_GENERAL_DB],
            [self::WEB_VISITOR_COUNT, 'Lượt View', self::TYPE_INT_DB, 0, self::CATEGORY_GENERAL_DB],

            [self::EXCHANGE_USD_RATE, 'Tỉ Lệ Quy Đổi USD', self::TYPE_INT_DB, 22000, self::CATEGORY_GENERAL_DB],
            [self::EXCHANGE_POINT_RATE, 'Tỉ Lệ Quy Đổi Điểm', self::TYPE_INT_DB, 1000, self::CATEGORY_GENERAL_DB],

            [self::FACEBOOK_APP_ID, 'Facebook App Id', self::TYPE_STRING_DB, '', self::CATEGORY_SOCIAL_DB],
            [self::FACEBOOK_APP_SECRET, 'Facebook App Secret', self::TYPE_STRING_DB, '', self::CATEGORY_SOCIAL_DB],
            [self::FACEBOOK_GRAPH_VERSION, 'Facebook Graph Version', self::TYPE_STRING_DB, 'v2.9', self::CATEGORY_SOCIAL_DB],
            [self::FACEBOOK_PAGE_URL, 'Facebook Page', self::TYPE_STRING_DB, '', self::CATEGORY_SOCIAL_DB],

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
            $setting = Setting::select('id')->where('code', $coreSetting[0])->first();

            if(empty($setting))
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
    }

    public static function getSettings($category = self::CATEGORY_GENERAL_DB, $code = null)
    {
        if(empty(self::$settings) || !isset(self::$settings[$category]))
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

    public static function increaseVisitorCount()
    {
        $settings = self::getSettings(self::CATEGORY_GENERAL_DB);

        $settings[self::WEB_VISITOR_COUNT]->increment('value', 1);
    }
}