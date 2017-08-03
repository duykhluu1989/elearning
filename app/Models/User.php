<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Libraries\Helpers\Utility;

class User extends Authenticatable
{
    const STATUS_ACTIVE_LABEL = 'Hợp Lệ';
    const STATUS_INACTIVE_LABEL = 'Trục Xuất';

    const AVATAR_UPLOAD_PATH = '/uploads/users/avatars';

    protected $table = 'user';

    public $timestamps = false;

    public function profile()
    {
        return $this->hasOne('App\Models\Profile', 'user_id');
    }

    public function userRoles()
    {
        return $this->hasMany('App\Models\UserRole', 'user_id');
    }

    public function collaboratorInformation()
    {
        return $this->hasOne('App\Models\Collaborator', 'user_id');
    }

    public function studentInformation()
    {
        return $this->hasOne('App\Models\Student', 'user_id');
    }

    public function teacherInformation()
    {
        return $this->hasOne('App\Models\Teacher', 'user_id');
    }

    public function expertInformation()
    {
        return $this->hasOne('App\Models\Expert', 'user_id');
    }

    public static function initCoreUser()
    {
        $user = new User();
        $user->username = 'admin';
        $user->password = Hash::make('123456');
        $user->status = Utility::ACTIVE_DB;
        $user->email = 'admin@caydenthan.vn';
        $user->admin = Utility::ACTIVE_DB;
        $user->created_at = date('Y-m-d H:i:s');
        $user->collaborator = Utility::INACTIVE_DB;
        $user->teacher = Utility::INACTIVE_DB;
        $user->expert = Utility::INACTIVE_DB;
        $user->save();
    }

    public static function getUserStatus($value = null)
    {
        $status = [
            Utility::ACTIVE_DB => self::STATUS_ACTIVE_LABEL,
            Utility::INACTIVE_DB => self::STATUS_INACTIVE_LABEL,
        ];

        if($value !== null && isset($status[$value]))
            return $status[$value];

        return $status;
    }

    public function learnCourseNow(Course $course)
    {
        if($course->validatePromotionPrice())
            $coursePrice = $course->promotionPrice->price;
        else
            $coursePrice = $course->price;

        if($coursePrice > 0)
            return false;

        $userCourse = UserCourse::where('user_id', $this->id)->where('course_id', $course->id)->first();

        if(!empty($userCourse))
            return true;

        try
        {
            DB::beginTransaction();

            $userCourse = new UserCourse();
            $userCourse->user_id = $this->id;
            $userCourse->course_id = $course->id;
            $userCourse->save();

            $course->bought_count ++;
            $course->save();

            if(empty($this->studentInformation))
            {
                $student = new Student();
                $student->user_id = $this->id;
                $student->course_count = 1;
                $student->save();
            }
            else
            {
                $this->studentInformation->course_count += 1;
                $this->studentInformation->save();
            }

            DB::commit();

            return true;
        }
        catch(\Exception $e)
        {
            DB::rollBack();

            return false;
        }
    }
}
