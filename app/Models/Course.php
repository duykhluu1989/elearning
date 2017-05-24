<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    const STATUS_PUBLISH_DB = 2;
    const STATUS_FINISH_DB = 1;
    const STATUS_DRAFT_DB = 0;
    const STATUS_PUBLISH_LABEL = 'Xuất Bản';
    const STATUS_FINISH_LABEL = 'Hoàn Thành';
    const STATUS_DRAFT_LABEL = 'Nháp';

    const TYPE_HIGHLIGHT_DB = 1;
    const TYPE_NORMAL_DB = 0;
    const TYPE_HIGHLIGHT_LABEL = 'Nổi Bật';
    const TYPE_NORMAL_LABEL = 'Bình Thường';

    protected $table = 'course';

    public $timestamps = false;

    public function user()
    {
        return $this->belongsTo('App\Models\User', 'user_id');
    }

    public function level()
    {
        return $this->belongsTo('App\Models\Level', 'level_id');
    }

    public function categoryCourses()
    {
        return $this->hasMany('App\Models\CategoryCourse', 'course_id');
    }

    public static function getCourseStatus($value = null)
    {
        $status = [
            self::STATUS_PUBLISH_DB => self::STATUS_PUBLISH_LABEL,
            self::STATUS_FINISH_DB => self::STATUS_FINISH_LABEL,
            self::STATUS_DRAFT_DB => self::STATUS_DRAFT_LABEL,
        ];

        if($value !== null && isset($status[$value]))
            return $status[$value];

        return $status;
    }

    public static function getCourseType($value = null)
    {
        $type = [
            self::TYPE_HIGHLIGHT_DB => self::TYPE_HIGHLIGHT_LABEL,
            self::TYPE_NORMAL_DB => self::TYPE_NORMAL_LABEL,
        ];

        if($value !== null && isset($type[$value]))
            return $type[$value];

        return $type;
    }
}