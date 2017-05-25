<div class="box box-primary">
    <div class="box-header with-border">
        <button type="submit" class="btn btn-primary">{{ empty($courseItem->id) ? 'Tạo Mới' : 'Cập Nhật' }}</button>
        <a href="{{ action('Backend\CourseController@adminCourseItem', ['id' => $courseItem->course_id]) }}" class="btn btn-default">Quay Lại</a>
    </div>
    <div class="box-body">
    </div>
</div>