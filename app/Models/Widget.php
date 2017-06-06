<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Libraries\Helpers\Utility;

class Widget extends Model
{
    const HOME_SLIDER = 'HOME_SLIDER';

    const TYPE_SLIDER_DB = 0;
    const TYPE_SLIDER_LABEL = 'Khung Ảnh Trượt';

    const ATTRIBUTE_TYPE_STRING_DB = 0;
    const ATTRIBUTE_TYPE_INT_DB = 1;
    const ATTRIBUTE_TYPE_JSON_DB = 2;
    const ATTRIBUTE_TYPE_IMAGE_DB = 3;

    protected $table = 'widget';

    public $timestamps = false;

    public static function initCoreWidgets()
    {
        $coreWidgets = [
            [
                self::HOME_SLIDER,
                'Khung Ảnh Trượt Trang Chủ',
                Utility::ACTIVE_DB,
                self::TYPE_SLIDER_DB,
                json_encode([
                    [
                        'title' => 'Ảnh',
                        'name' => 'image',
                        'type' => self::ATTRIBUTE_TYPE_IMAGE_DB,
                    ],
                    [
                        'title' => 'Tiêu Đề',
                        'name' => 'title',
                        'type' => self::ATTRIBUTE_TYPE_STRING_DB,
                    ],
                    [
                        'title' => 'Tiêu Đề EN',
                        'name' => 'title_en',
                        'type' => self::ATTRIBUTE_TYPE_STRING_DB,
                    ],
                    [
                        'title' => 'Mô Tả',
                        'name' => 'description',
                        'type' => self::ATTRIBUTE_TYPE_STRING_DB,
                    ],
                    [
                        'title' => 'Mô Tả EN',
                        'name' => 'description_en',
                        'type' => self::ATTRIBUTE_TYPE_STRING_DB,
                    ],
                    [
                        'title' => 'Đường Dẫn',
                        'name' => 'url',
                        'type' => self::ATTRIBUTE_TYPE_STRING_DB,
                    ],
                ]),
            ],
        ];

        foreach($coreWidgets as $coreWidget)
        {
            $widget = new Widget();
            $widget->code = $coreWidget[0];
            $widget->name = $coreWidget[1];
            $widget->status = $coreWidget[2];
            $widget->type = $coreWidget[3];
            $widget->attribute = $coreWidget[4];
            $widget->save();
        }
    }

    public static function getWidgetType($value = null)
    {
        $type = [
            self::TYPE_SLIDER_DB => self::TYPE_SLIDER_LABEL,
        ];

        if($value !== null && isset($type[$value]))
            return $type[$value];

        return $type;
    }
}