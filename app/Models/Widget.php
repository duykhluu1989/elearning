<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Libraries\Helpers\Utility;

class Widget extends Model
{
    const HOME_SLIDER = 'home_slider';
    const GROUP_FREE_COURSE = 'group_free_course';
    const GROUP_HIGHLIGHT_COURSE = 'group_highlight_course';
    const GROUP_DISCOUNT_COURSE = 'group_discount_course';
    const ADVERTISE_HORIZONTAL_TOP = 'advertise_horizontal_top';
    const ADVERTISE_VERTICAL_LEFT = 'advertise_vertical_left';
    const ADVERTISE_VERTICAL_RIGHT = 'advertise_vertical_right';

    const TYPE_SLIDER_DB = 0;
    const TYPE_GROUP_COURSE_DB = 1;
    const TYPE_GROUP_COURSE_CUSTOM_DB = 2;
    const TYPE_ADVERTISE_BANNER_DB = 3;
    const TYPE_SLIDER_LABEL = 'Khung Ảnh Trượt';
    const TYPE_GROUP_COURSE_LABEL = 'Nhóm Khóa Học';
    const TYPE_GROUP_COURSE_CUSTOM_LABEL = 'Nhóm Khóa Học Tùy Chọn';
    const TYPE_ADVERTISE_BANNER_LABEL = 'Biển Quảng Cáo';

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
            [
                self::GROUP_FREE_COURSE,
                'Nhóm Khóa Học Miễn Phí',
                Utility::ACTIVE_DB,
                self::TYPE_GROUP_COURSE_DB,
                json_encode([
                    [
                        'title' => 'Khóa Học',
                        'name' => 'course_id',
                        'type' => self::ATTRIBUTE_TYPE_INT_DB,
                    ],
                ]),
            ],
            [
                self::GROUP_HIGHLIGHT_COURSE,
                'Nhóm Khóa Học Nổi Bật',
                Utility::ACTIVE_DB,
                self::TYPE_GROUP_COURSE_DB,
                json_encode([
                    [
                        'title' => 'Khóa Học',
                        'name' => 'course_id',
                        'type' => self::ATTRIBUTE_TYPE_INT_DB,
                    ],
                ]),
            ],
            [
                self::GROUP_DISCOUNT_COURSE,
                'Nhóm Khóa Học Giảm Giá',
                Utility::ACTIVE_DB,
                self::TYPE_GROUP_COURSE_DB,
                json_encode([
                    [
                        'title' => 'Khóa Học',
                        'name' => 'course_id',
                        'type' => self::ATTRIBUTE_TYPE_INT_DB,
                    ],
                ]),
            ],
            [
                self::ADVERTISE_HORIZONTAL_TOP,
                'Biển Quảng Cáo Ngang',
                Utility::ACTIVE_DB,
                self::TYPE_ADVERTISE_BANNER_DB,
                json_encode([
                    [
                        'title' => 'Ảnh',
                        'name' => 'image',
                        'type' => self::ATTRIBUTE_TYPE_IMAGE_DB,
                    ],
                    [
                        'title' => 'Đường Dẫn',
                        'name' => 'url',
                        'type' => self::ATTRIBUTE_TYPE_STRING_DB,
                    ],
                    [
                        'title' => 'Script',
                        'name' => 'script',
                        'type' => self::ATTRIBUTE_TYPE_STRING_DB,
                    ],
                ]),
            ],
            [
                self::ADVERTISE_VERTICAL_LEFT,
                'Biển Quảng Cáo Dọc Trái',
                Utility::ACTIVE_DB,
                self::TYPE_ADVERTISE_BANNER_DB,
                json_encode([
                    [
                        'title' => 'Ảnh',
                        'name' => 'image',
                        'type' => self::ATTRIBUTE_TYPE_IMAGE_DB,
                    ],
                    [
                        'title' => 'Đường Dẫn',
                        'name' => 'url',
                        'type' => self::ATTRIBUTE_TYPE_STRING_DB,
                    ],
                    [
                        'title' => 'Script',
                        'name' => 'script',
                        'type' => self::ATTRIBUTE_TYPE_STRING_DB,
                    ],
                ]),
            ],
            [
                self::ADVERTISE_VERTICAL_RIGHT,
                'Biển Quảng Cáo Dọc Phải',
                Utility::ACTIVE_DB,
                self::TYPE_ADVERTISE_BANNER_DB,
                json_encode([
                    [
                        'title' => 'Ảnh',
                        'name' => 'image',
                        'type' => self::ATTRIBUTE_TYPE_IMAGE_DB,
                    ],
                    [
                        'title' => 'Đường Dẫn',
                        'name' => 'url',
                        'type' => self::ATTRIBUTE_TYPE_STRING_DB,
                    ],
                    [
                        'title' => 'Script',
                        'name' => 'script',
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
            self::TYPE_GROUP_COURSE_DB => self::TYPE_GROUP_COURSE_LABEL,
            self::TYPE_GROUP_COURSE_CUSTOM_DB => self::TYPE_GROUP_COURSE_CUSTOM_LABEL,
            self::TYPE_ADVERTISE_BANNER_DB => self::TYPE_ADVERTISE_BANNER_LABEL,
        ];

        if($value !== null && isset($type[$value]))
            return $type[$value];

        return $type;
    }
}