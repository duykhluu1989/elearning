<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Libraries\Helpers\Utility;

class Category extends Model
{
    protected $table = 'category';

    public $timestamps = false;

    protected static function boot()
    {
        parent::boot();

        static::saving(function(Category $category) {
            static::changeParentIdSavingHandle($category);

            static::changeStatusOrParentStatusSavingHandle($category);
        });
    }

    protected static function changeParentIdSavingHandle(Category $category)
    {
        if($category->getOriginal('parent_id') != $category->parent_id)
        {
            $categoryIds[] = $category->id;

            if(empty($category->parent_id))
                $category->parent_status = $category->status;
            else
            {
                $setParentStatusHide = false;

                $tempCategory = $category;

                do
                {
                    $parentCategory = Category::select('id', 'parent_id', 'status', 'parent_status')->find($tempCategory->parent_id);

                    $categoryIds[] = $parentCategory->id;

                    if($parentCategory->status == Utility::INACTIVE_DB || $parentCategory->parent_status == Utility::INACTIVE_DB)
                    {
                        $category->parent_status = Utility::INACTIVE_DB;

                        $setParentStatusHide = true;
                    }

                    $tempCategory = $parentCategory;
                }
                while(!empty($tempCategory->parent_id));

                if($setParentStatusHide == false)
                    $category->parent_status = Utility::ACTIVE_DB;
            }

            $categoryIds = array_reverse($categoryIds);

            $currentCategoryLevel = count($categoryIds);

            $page = 1;

            do
            {
                $courses = Course::select('course.id')
                    ->with(['categoryCourses' => function($query) {
                        $query->orderBy('level');
                    }])
                    ->join('category_course', 'course.id', '=', 'category_course.course_id')
                    ->where('category_course.category_id', $category->id)
                    ->paginate(Utility::LARGE_SET_LIMIT, ['*'], 'page', $page);

                foreach($courses as $course)
                {
                    $isChild = false;

                    $i = 1;

                    foreach($course->categoryCourses as $categoryCourse)
                    {
                        if($categoryCourse->category_id == $category->id)
                        {
                            $categoryCourse->level = $currentCategoryLevel;
                            $categoryCourse->save();

                            $isChild = true;
                        }
                        else
                        {
                            if($isChild == false)
                                $categoryCourse->delete();
                            else
                            {
                                $categoryCourse->level = $currentCategoryLevel + $i;
                                $categoryCourse->save();

                                $i ++;
                            }
                        }
                    }

                    foreach($categoryIds as $key => $categoryId)
                    {
                        if($categoryId != $category->id)
                        {
                            $categoryCourse = new CategoryCourse();
                            $categoryCourse->category_id = $categoryId;
                            $categoryCourse->course_id = $course->id;
                            $categoryCourse->level = $key + 1;
                            $categoryCourse->save();
                        }
                    }
                }


                $page ++;

                $countCourses = count($courses);
            }
            while($countCourses > 0);
        }
    }

    protected static function changeStatusOrParentStatusSavingHandle(Category $category)
    {
        if($category->getOriginal('status') != $category->status || $category->getOriginal('parent_status') != $category->parent_status)
        {
            if($category->status == Utility::INACTIVE_DB || $category->parent_status == Utility::INACTIVE_DB)
                $parentStatus = Utility::INACTIVE_DB;
            else
                $parentStatus = Utility::ACTIVE_DB;

            $childrenCategories = Category::select('id', 'parent_id', 'status', 'parent_status')->where('parent_id', $category->id)->get();

            foreach($childrenCategories as $childCategory)
            {
                $childCategory->parent_status = $parentStatus;
                $childCategory->save();
            }

            $page = 1;

            do
            {
                $courses = Course::select('course.id', 'course.category_status')
                    ->with(['categoryCourses' => function($query) {
                        $query->orderBy('level', 'desc');
                    }, 'categoryCourses.category' => function($query) {
                        $query->select('id', 'status', 'parent_status');
                    }])
                    ->join('category_course', 'course.id', '=', 'category_course.course_id')
                    ->where('category_course.category_id', $category->id)
                    ->paginate(Utility::LARGE_SET_LIMIT, ['*'], 'page', $page);

                foreach($courses as $course)
                {
                    if($course->categoryCourses[0]->category->status == Utility::INACTIVE_DB || $course->categoryCourses[0]->category->parent_status == Utility::INACTIVE_DB )
                        $course->category_status = Utility::INACTIVE_DB;
                    else
                        $course->category_status = Utility::ACTIVE_DB;

                    $course->save();
                }

                $page ++;

                $countCourses = count($courses);
            }
            while($countCourses > 0);
        }
    }

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