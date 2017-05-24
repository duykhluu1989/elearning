<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    const STATUS_ACTIVE_DB = 1;
    const STATUS_INACTIVE_DB = 0;
    const STATUS_ACTIVE_LABEL = 'Mở';
    const STATUS_INACTIVE_LABEL = 'Đóng';

    protected $table = 'category';

    public $timestamps = false;

    public function parentCategory()
    {
        return $this->belongsTo('App\Models\Category', 'parent_id');
    }

    public static function getCategoryStatus($value = null)
    {
        $status = [
            self::STATUS_ACTIVE_DB => self::STATUS_ACTIVE_LABEL,
            self::STATUS_INACTIVE_DB => self::STATUS_INACTIVE_LABEL,
        ];

        if($value !== null && isset($status[$value]))
            return $status[$value];

        return $status;
    }

    public function countCategoryCourses()
    {
        return CategoryCourse::where('category_id', $this->id)->count('id');
    }
}