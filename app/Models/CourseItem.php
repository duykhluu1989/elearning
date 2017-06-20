<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CourseItem extends Model
{
    const TYPE_TEXT_DB = 0;
    const TYPE_VIDEO_DB = 1;
    const TYPE_AUDIO_DB = 2;
    const TYPE_TEXT_LABEL = 'Văn Bản';
    const TYPE_VIDEO_LABEL = 'Video';
    const TYPE_AUDIO_LABEL = 'Audio';

    protected $table = 'course_item';

    public $timestamps = false;

    public function course()
    {
        return $this->belongsTo('App\Models\Course', 'course_id');
    }

    public static function getCourseItemType($value = null)
    {
        $type = [
            self::TYPE_TEXT_DB => self::TYPE_TEXT_LABEL,
            self::TYPE_VIDEO_DB => self::TYPE_VIDEO_LABEL,
            self::TYPE_AUDIO_DB => self::TYPE_AUDIO_LABEL,
        ];

        if($value !== null && isset($type[$value]))
            return $type[$value];

        return $type;
    }

    public function isDeletable()
    {
        return false;
    }
}