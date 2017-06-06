<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DiscountApply extends Model
{
    const TARGET_CATEGORY_DB = 'category';
    const TARGET_COURSE_DB = 'course';
    const TARGET_CATEGORY_LABEL = 'Chủ Đề';
    const TARGET_COURSE_LABEL = 'Khóa Học';

    protected $table = 'discount_apply';

    public $timestamps = false;

    public function discount()
    {
        return $this->belongsTo('App\Models\Discount', 'discount_id');
    }

    public function apply()
    {
        if($this->target == self::TARGET_CATEGORY_DB)
            return $this->belongsTo('App\Models\Category', 'apply_id');
        else
            return $this->belongsTo('App\Models\Course', 'apply_id');
    }

    public static function getDiscountApplyTarget($value = null)
    {
        $target = [
            self::TARGET_CATEGORY_DB => self::TARGET_CATEGORY_LABEL,
            self::TARGET_COURSE_DB => self::TARGET_COURSE_LABEL
        ];

        if($value !== null && isset($target[$value]))
            return $target[$value];

        return $target;
    }
}