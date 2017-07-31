<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CourseReview extends Model
{
    const STATUS_PENDING_DB = 2;
    const STATUS_ACTIVE_DB = 1;
    const STATUS_INACTIVE_DB = 0;
    const STATUS_PENDING_LABEL = 'Chờ Duyệt';
    const STATUS_ACTIVE_LABEL = 'Hiển Thị';
    const STATUS_INACTIVE_LABEL = 'Ẩn Đi';

    protected $table = 'course_review';

    public $timestamps = false;

    public function user()
    {
        return $this->belongsTo('App\Models\User', 'user_id');
    }

    public function course()
    {
        return $this->belongsTo('App\Models\Course', 'course_id');
    }

    public static function getCourseReviewStatus($value = null)
    {
        $status = [
            self::STATUS_ACTIVE_DB => self::STATUS_ACTIVE_LABEL,
            self::STATUS_INACTIVE_DB => self::STATUS_INACTIVE_LABEL,
            self::STATUS_PENDING_DB => self::STATUS_PENDING_LABEL,
        ];

        if($value !== null && isset($status[$value]))
            return $status[$value];

        return $status;
    }
}