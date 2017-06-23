<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $table = 'category';

    public $timestamps = false;

    public function parentCategory()
    {
        return $this->belongsTo('App\Models\Category', 'parent_id');
    }

    public function childrenCategories()
    {
        return $this->hasMany('App\Models\Category', 'parent_id');
    }

    public function countChildrenCategories()
    {
        return Category::where('parent_id', $this->id)->count('id');
    }

    public function countCategoryCourses()
    {
        return CategoryCourse::where('category_id', $this->id)->count('id');
    }

    public function countDiscountApplies()
    {
        return DiscountApply::where('apply_id', $this->id)->where('target', DiscountApply::TARGET_CATEGORY_DB)->count('id');
    }

    public function isDeletable()
    {
        if($this->countChildrenCategories() > 0 || $this->countCategoryCourses() > 0 || $this->countDiscountApplies() > 0)
            return false;

        return true;
    }
}