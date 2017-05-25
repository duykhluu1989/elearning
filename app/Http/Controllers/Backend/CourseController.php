<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Libraries\Widgets\GridView;
use App\Libraries\Helpers\Html;
use App\Libraries\Helpers\Utility;
use App\Models\Category;
use App\Models\Level;
use App\Models\Course;
use App\Models\CourseItem;
use App\Models\CategoryCourse;
use App\Models\User;
use FFMpeg\FFProbe;

class CourseController extends Controller
{
    public function adminCategory(Request $request)
    {
        $dataProvider = Category::with('parentCategory')->select('id', 'parent_id', 'name', 'status', 'order', 'code')->orderBy('id', 'desc');

        $inputs = $request->all();

        if(count($inputs) > 0)
        {
            if(!empty($inputs['name']))
                $dataProvider->where('name', 'like', '%' . $inputs['name'] . '%');

            if(!empty($inputs['code']))
                $dataProvider->where('code', 'like', '%' . $inputs['code'] . '%');

            if(isset($inputs['status']) && $inputs['status'] !== '')
                $dataProvider->where('status', $inputs['status']);
        }

        $dataProvider = $dataProvider->paginate(GridView::ROWS_PER_PAGE);

        $columns = [
            [
                'title' => 'Tên',
                'data' => function($row) {
                    echo Html::a($row->name, [
                        'href' => action('Backend\CourseController@editCategory', ['id' => $row->id]),
                    ]);
                },
            ],
            [
                'title' => 'Mã',
                'data' => 'code',
            ],
            [
                'title' => 'Thứ Tự',
                'data' => 'order',
            ],
            [
                'title' => 'Chủ Đề Cha',
                'data' => function($row) {
                    if(!empty($row->parentCategory))
                        echo $row->parentCategory->name;
                },
            ],
            [
                'title' => 'Trạng Thái',
                'data' => function($row) {
                    $status = Category::getCategoryStatus($row->status);
                    if($row->status == Category::STATUS_ACTIVE_DB)
                        echo Html::span($status, ['class' => 'text-green']);
                    else
                        echo Html::span($status, ['class' => 'text-red']);
                },
            ],
        ];

        $gridView = new GridView($dataProvider, $columns);
        $gridView->setCheckbox();
        $gridView->setFilters([
            [
                'title' => 'Tên',
                'name' => 'name',
                'type' => 'input',
            ],
            [
                'title' => 'Mã',
                'name' => 'code',
                'type' => 'input',
            ],
            [
                'title' => 'Trạng Thái',
                'name' => 'status',
                'type' => 'select',
                'options' => Category::getCategoryStatus(),
            ],
        ]);
        $gridView->setFilterValues($inputs);

        return view('backend.courses.admin_category', [
            'gridView' => $gridView,
        ]);
    }

    public function createCategory(Request $request)
    {
        $category = new Category();
        $category->status = Category::STATUS_ACTIVE_DB;
        $category->order = 1;

        return $this->saveCategory($request, $category);
    }

    public function editCategory(Request $request, $id)
    {
        $category = Category::find($id);

        if(empty($category))
            return view('backend.errors.404');

        return $this->saveCategory($request, $category, false);
    }

    protected function saveCategory($request, $category, $create = true)
    {
        if($request->isMethod('post'))
        {
            $inputs = $request->all();

            $validator = Validator::make($inputs, [
                'name' => 'required|unique:category,name' . ($create == true ? '' : (',' . $category->id)),
                'name_en' => 'nullable|unique:category,name_en' . ($create == true ? '' : (',' . $category->id)),
                'code' => 'required|alpha_num|unique:category,code' . ($create == true ? '' : (',' . $category->id)),
                'slug' => 'nullable|unique:category,slug' . ($create == true ? '' : (',' . $category->id)),
                'slug_en' => 'nullable|unique:category,slug_en' . ($create == true ? '' : (',' . $category->id)),
                'order' => 'required|integer|min:1',
            ]);

            $validator->after(function($validator) use(&$inputs, $category, $create) {
                if(empty($inputs['parent_name']))
                    $inputs['parent_id'] = null;
                else
                {
                    $parentCategory = Category::select('id', 'parent_id')->where('name', $inputs['parent_name'])->first();

                    if(empty($parentCategory))
                        $validator->errors()->add('parent_name', 'Chủ Đề Cha Không Tồn Tại');
                    else
                    {
                        if($create == false)
                        {
                            $tempParentCategory = $parentCategory;

                            while(!empty($tempParentCategory->parentCategory))
                            {
                                $tempParentCategory = $tempParentCategory->parentCategory;

                                if($tempParentCategory->id == $category->id)
                                {
                                    $validator->errors()->add('parent_name', 'Chủ Đề Cha Không Được Là Chủ Đề Con Của Chính Nó');
                                    break;
                                }
                            }
                        }

                        $inputs['parent_id'] = $parentCategory->id;
                    }
                }
            });

            if($validator->passes())
            {
                $category->name = $inputs['name'];
                $category->name_en = $inputs['name_en'];
                $category->status = $inputs['status'];
                $category->order = $inputs['order'];
                $category->code = strtoupper($inputs['code']);
                $category->parent_id = $inputs['parent_id'];

                if(empty($inputs['slug']))
                    $category->slug = str_slug($category->name);
                else
                    $category->slug = str_slug($inputs['slug']);

                if(empty($inputs['slug_en']))
                    $category->slug_en = str_slug($category->name_en);
                else
                    $category->slug_en = str_slug($inputs['slug_en']);

                $category->save();

                return redirect()->action('Backend\CourseController@editCategory', ['id' => $category->id])->with('message', 'Success');
            }
            else
            {
                if($create == true)
                    return redirect()->action('Backend\CourseController@createCategory')->withErrors($validator)->withInput();
                else
                    return redirect()->action('Backend\CourseController@editCategory', ['id' => $category->id])->withErrors($validator)->withInput();
            }
        }

        if($create == true)
        {
            return view('backend.courses.create_category', [
                'category' => $category,
            ]);
        }
        else
        {
            return view('backend.courses.edit_category', [
                'category' => $category,
            ]);
        }
    }

    public function deleteCategory($id)
    {
        $category = Category::find($id);

        if(empty($category) || $category->countCategoryCourses() > 0)
            return view('backend.errors.404');

        $category->delete();

        return redirect()->action('Backend\CourseController@adminCategory')->with('message', 'Success');
    }

    public function controlDeleteCategory(Request $request)
    {
        $ids = $request->input('ids');

        $categories = Category::whereIn('id', explode(';', $ids))->get();

        foreach($categories as $category)
        {
            if($category->countCategoryCourses() == 0)
                $category->delete();
        }

        return redirect()->action('Backend\CourseController@adminCategory')->with('message', 'Success');
    }

    public function autoCompleteCategory(Request $request)
    {
        $term = $request->input('term');
        $except = $request->input('except');

        $builder = Category::select('id', 'name')->where('name', 'like', '%' . $term . '%')->limit(Utility::AUTO_COMPLETE_LIMIT);

        if(!empty($except))
            $builder->where('id', '<>', $except);

        $categories = $builder->get()->toJson();

        return $categories;
    }

    public function adminLevel()
    {
        $dataProvider = Level::select('id', 'name', 'name_en', 'order')->orderBy('id', 'desc')->paginate(GridView::ROWS_PER_PAGE);

        $columns = [
            [
                'title' => 'Tên',
                'data' => function($row) {
                    echo Html::a($row->name, [
                        'href' => action('Backend\CourseController@editLevel', ['id' => $row->id]),
                    ]);
                },
            ],
            [
                'title' => 'Tên EN',
                'data' => 'name_en',
            ],
            [
                'title' => 'Thứ Tự',
                'data' => 'order',
            ],
        ];

        $gridView = new GridView($dataProvider, $columns);
        $gridView->setCheckbox();

        return view('backend.courses.admin_level', [
            'gridView' => $gridView,
        ]);

    }

    public function createLevel(Request $request)
    {
        $level = new Level();
        $level->order = 1;

        return $this->saveLevel($request, $level);
    }

    public function editLevel(Request $request, $id)
    {
        $level = Level::find($id);

        if(empty($level))
            return view('backend.errors.404');

        return $this->saveLevel($request, $level, false);
    }

    protected function saveLevel($request, $level, $create = true)
    {
        if($request->isMethod('post'))
        {
            $inputs = $request->all();

            $validator = Validator::make($inputs, [
                'name' => 'required|unique:level,name' . ($create == true ? '' : (',' . $level->id)),
                'name_en' => 'nullable|unique:level,name_en' . ($create == true ? '' : (',' . $level->id)),
                'order' => 'required|integer|min:1',
            ]);

            if($validator->passes())
            {
                $level->name = $inputs['name'];
                $level->name_en = $inputs['name_en'];
                $level->order = $inputs['order'];
                $level->save();

                return redirect()->action('Backend\CourseController@editLevel', ['id' => $level->id])->with('message', 'Success');
            }
            else
            {
                if($create == true)
                    return redirect()->action('Backend\CourseController@createLevel')->withErrors($validator)->withInput();
                else
                    return redirect()->action('Backend\CourseController@editLevel', ['id' => $level->id])->withErrors($validator)->withInput();
            }
        }

        if($create == true)
        {
            return view('backend.courses.create_level', [
                'level' => $level,
            ]);
        }
        else
        {
            return view('backend.courses.edit_level', [
                'level' => $level,
            ]);
        }
    }

    public function deleteLevel($id)
    {
        $level = Level::find($id);

        if(empty($level) || $level->countCourses() > 0)
            return view('backend.errors.404');

        $level->delete();

        return redirect()->action('Backend\CourseController@adminLevel')->with('message', 'Success');
    }

    public function controlDeleteLevel(Request $request)
    {
        $ids = $request->input('ids');

        $levels = Level::whereIn('id', explode(';', $ids))->get();

        foreach($levels as $level)
        {
            if($level->countCourses() == 0)
                $level->delete();
        }

        return redirect()->action('Backend\CourseController@adminLevel')->with('message', 'Success');
    }

    public function adminCourse(Request $request)
    {
        $dataProvider = Course::with(['user.profile', 'categoryCourses' => function($query) {
            $query->orderBy('level', 'desc')->limit(1);
        }, 'categoryCourses.category'])
            ->select('course.id', 'course.name', 'course.user_id', 'course.price', 'course.status', 'course.type', 'course.code')->orderBy('id', 'desc');

        $inputs = $request->all();

        if(count($inputs) > 0)
        {
            if(!empty($inputs['name']))
                $dataProvider->where('course.name', 'like', '%' . $inputs['name'] . '%');

            if(!empty($inputs['user_name']))
            {
                $dataProvider->join('user', 'course.user_id', '=', 'user.id')
                    ->join('profile', 'user.id', '=', 'profile.user_id')
                    ->where('profile.name', 'like', '%' . $inputs['user_name'] . '%');
            }

            if(!empty($inputs['category_name']))
            {
                $dataProvider->join('category_course', 'course.id', '=', 'category_course.course_id')
                    ->join('category', 'category_course.category_id', '=', 'category.id')
                    ->where('category.name', 'like', '%' . $inputs['category_name'] . '%');
            }

            if(!empty($inputs['code']))
                $dataProvider->where('course.code', 'like', '%' . $inputs['code'] . '%');

            if(isset($inputs['status']) && $inputs['status'] !== '')
                $dataProvider->where('course.status', $inputs['status']);

            if(isset($inputs['type']) && $inputs['type'] !== '')
                $dataProvider->where('course.type', $inputs['type']);
        }

        $dataProvider = $dataProvider->paginate(GridView::ROWS_PER_PAGE);

        $columns = [
            [
                'title' => 'Tên',
                'data' => function($row) {
                    echo Html::a($row->name, [
                        'href' => action('Backend\CourseController@editCourse', ['id' => $row->id]),
                    ]);
                },
            ],
            [
                'title' => 'Giảng Viên',
                'data' => function($row) {
                    echo $row->user->profile->name;
                },
            ],
            [
                'title' => 'Chủ Đề',
                'data' => function($row) {
                    echo $row->categoryCourses[0]->category->name;
                },
            ],
            [
                'title' => 'Mã',
                'data' => 'code',
            ],
            [
                'title' => 'Giá Tiền (VND)',
                'data' => function($row) {
                    echo Utility::formatNumber($row->price);
                },
            ],
            [
                'title' => 'Loại',
                'data' => function($row) {
                    echo Course::getCourseType($row->type);
                },
            ],
            [
                'title' => 'Trạng Thái',
                'data' => function($row) {
                    $status = Course::getCourseStatus($row->status);
                    if($row->status == Course::STATUS_PUBLISH_DB)
                        echo Html::span($status, ['class' => 'text-green']);
                    else if($row->status == \App\Models\Course::STATUS_FINISH_DB)
                        echo Html::span($status, ['class' => 'text-light-blue']);
                    else
                        echo Html::span($status, ['class' => 'text-red']);
                },
            ],
            [
                'title' => '',
                'data' => function($row) {
                    echo Html::a(Html::i('', ['class' => 'fa fa-list-ul fa-fw']), [
                        'href' => action('Backend\CourseController@adminCourseItem', ['id' => $row->id]),
                        'class' => 'btn btn-primary',
                        'data-container' => 'body',
                        'data-toggle' => 'popover',
                        'data-placement' => 'top',
                        'data-content' => 'Danh Sách Bài Học',
                    ]);
                },
            ]
        ];

        $gridView = new GridView($dataProvider, $columns);
        $gridView->setCheckbox();
        $gridView->setFilters([
            [
                'title' => 'Tên',
                'name' => 'name',
                'type' => 'input',
            ],
            [
                'title' => 'Giảng Viên',
                'name' => 'user_name',
                'type' => 'input',
            ],
            [
                'title' => 'Chủ Đề',
                'name' => 'category_name',
                'type' => 'input',
            ],
            [
                'title' => 'Mã',
                'name' => 'code',
                'type' => 'input',
            ],
            [
                'title' => 'Loại',
                'name' => 'type',
                'type' => 'select',
                'options' => Course::getCourseType(),
            ],
            [
                'title' => 'Trạng Thái',
                'name' => 'status',
                'type' => 'select',
                'options' => Course::getCourseStatus(),
            ],
        ]);
        $gridView->setFilterValues($inputs);

        return view('backend.courses.admin_course', [
            'gridView' => $gridView,
        ]);
    }

    public function createCourse(Request $request)
    {
        $course = new Course();
        $course->status = Course::STATUS_DRAFT_DB;
        $course->type = Course::TYPE_NORMAL_DB;
        $course->price = 0;

        return $this->saveCourse($request, $course);
    }

    public function editCourse(Request $request, $id)
    {
        $course = Course::with(['user.profile', 'categoryCourses' => function($query) {
            $query->orderBy('level');
        }, 'categoryCourses.category'])->find($id);

        if(empty($course))
            return view('backend.errors.404');

        return $this->saveCourse($request, $course, false);
    }

    protected function saveCourse($request, $course, $create = true)
    {
        if($request->isMethod('post'))
        {
            $inputs = $request->all();

            $inputs['price'] = implode('', explode('.', $inputs['price']));

            if(!empty($inputs['point_price']))
                $inputs['point_price'] = implode('', explode('.', $inputs['point_price']));

            $validator = Validator::make($inputs, [
                'user_name' => 'required',
                'name' => 'required|unique:course,name' . ($create == true ? '' : (',' . $course->id)),
                'name_en' => 'nullable|unique:course,name_en' . ($create == true ? '' : (',' . $course->id)),
                'price' => 'required|integer|min:0',
                'description' => 'required',
                'point_price' => 'nullable|integer|min:1',
                'slug' => 'nullable|unique:course,slug' . ($create == true ? '' : (',' . $course->id)),
                'slug_en' => 'nullable|unique:course,slug_en' . ($create == true ? '' : (',' . $course->id)),
                'code' => 'required|alpha_num|unique:course,code' . ($create == true ? '' : (',' . $course->id)),
                'category_name' => 'required',
            ]);

            $validator->after(function($validator) use(&$inputs, $course, $create) {
                $teacherNameParts = explode(' - ', $inputs['user_name']);

                if(count($teacherNameParts) == 2)
                {
                    $user = User::select('user.id')
                        ->join('profile', 'user.id', '=', 'profile.user_id')
                        ->where('user.email', $teacherNameParts[1])
                        ->where('profile.name', $teacherNameParts[0])
                        ->first();

                    if(!empty($user))
                        $inputs['user_id'] = $user->id;
                }

                if(!isset($inputs['user_id']))
                    $validator->errors()->add('user_name', 'Giảng Viên Không Tồn Tại');

                $category = Category::select('id', 'parent_id')->where('name', $inputs['category_name'])->first();

                if(empty($category))
                    $validator->errors()->add('category_name', 'Chủ Đề Không Tồn Tại');
                else
                {
                    $categoryIds[] = $category->id;

                    while(!empty($category->parentCategory))
                    {
                        $category = $category->parentCategory;

                        $categoryIds[] = $category->id;
                    }

                    $inputs['categoryIds'] = array_reverse($categoryIds);
                }
            });

            if($validator->passes())
            {
                try
                {
                    DB::beginTransaction();

                    $course->user_id = $inputs['user_id'];
                    $course->name = $inputs['name'];
                    $course->name_en = $inputs['name_en'];
                    $course->price = $inputs['price'];
                    $course->status = $inputs['status'];
                    $course->description = $inputs['description'];
                    $course->description_en = $inputs['description_en'];
                    $course->point_price = $inputs['point_price'];
                    $course->code = strtoupper($inputs['code']);
                    $course->level_id = $inputs['level_id'];
                    $course->short_description = $inputs['short_description'];
                    $course->short_description_en = $inputs['short_description_en'];
                    $course->type = $inputs['type'];

                    if(empty($course->published_at) && $course->status == Course::STATUS_PUBLISH_DB)
                        $course->published_at = date('Y-m-d H:i:s');

                    if(empty($inputs['slug']))
                        $course->slug = str_slug($course->name);
                    else
                        $course->slug = str_slug($inputs['slug']);

                    if(empty($inputs['slug_en']))
                        $course->slug_en = str_slug($course->name_en);
                    else
                        $course->slug_en = str_slug($inputs['slug_en']);

                    $course->save();

                    foreach($course->categoryCourses as $categoryCourse)
                    {
                        $key = array_search($categoryCourse->category_id, $inputs['categoryIds']);

                        if($key !== false)
                        {
                            $categoryCourse->level = $key + 1;
                            $categoryCourse->save();

                            unset($inputs['categoryIds'][$key]);
                        }
                        else
                            $categoryCourse->delete();
                    }

                    foreach($inputs['categoryIds'] as $key => $categoryId)
                    {
                        $categoryCourse = new CategoryCourse();
                        $categoryCourse->category_id = $categoryId;
                        $categoryCourse->course_id = $course->id;
                        $categoryCourse->level = $key + 1;
                        $categoryCourse->save();
                    }

                    DB::commit();

                    return redirect()->action('Backend\CourseController@editCourse', ['id' => $course->id])->with('message', 'Success');
                }
                catch(\Exception $e)
                {
                    DB::rollBack();

                    if($create == true)
                        return redirect()->action('Backend\CourseController@createCourse')->withErrors(['category_name' => $e->getMessage()])->withInput();
                    else
                        return redirect()->action('Backend\CourseController@editCourse', ['id' => $course->id])->withErrors(['category_name' => $e->getMessage()])->withInput();
                }
            }
            else
            {
                if($create == true)
                    return redirect()->action('Backend\CourseController@createCourse')->withErrors($validator)->withInput();
                else
                    return redirect()->action('Backend\CourseController@editCourse', ['id' => $course->id])->withErrors($validator)->withInput();
            }
        }

        $levels = Level::pluck('name', 'id');

        if($create == true)
        {
            return view('backend.courses.create_course', [
                'course' => $course,
                'levels' => $levels,
            ]);
        }
        else
        {
            return view('backend.courses.edit_course', [
                'course' => $course,
                'levels' => $levels,
            ]);
        }
    }

    public function adminCourseItem(Request $request, $id)
    {
        $course = Course::with(['courseItems' => function($query) {
            $query->orderBy('number');
        }])->find($id);

        if(empty($course))
            return view('backend.errors.404');

        return view('backend.courses.admin_course_item', [
            'course' => $course,
        ]);
    }

    public function createCourseItem(Request $request, $id)
    {
        $course = Course::with(['courseItems' => function($query) {
            $query->select('id', 'course_id', 'number')->orderBy('number', 'desc')->limit(1);
        }])->find($id);

        if(empty($course))
            return view('backend.errors.404');

        $courseItem = new CourseItem();
        $courseItem->course_id = $course->id;
        $courseItem->type = CourseItem::TYPE_TEXT_DB;

        if(count($course->courseItems) > 0)
            $courseItem->number = $course->courseItems[0]->number + 1;
        else
            $courseItem->number = 1;

        $courseItem->course()->associate($course);

        return $this->saveCourseItem($request, $courseItem);
    }

    public function editCourseItem(Request $request, $id)
    {
        $courseItem = CourseItem::with('course')->find($id);

        if(empty($courseItem))
            return view('backend.errors.404');

        return $this->saveCourseItem($request, $courseItem, false);
    }

    protected function saveCourseItem($request, CourseItem $courseItem, $create = true)
    {
        if($request->isMethod('post'))
        {
            $inputs = $request->all();

            $validator = Validator::make($inputs, [
                'name' => 'required',
                'content' => 'required_if:type,' . CourseItem::TYPE_TEXT_DB,
                'video_path' => 'required_if:type,' . CourseItem::TYPE_VIDEO_DB,
            ]);

            if($validator->passes())
            {
                try
                {
                    DB::beginTransaction();

                    $courseItem->name = $inputs['name'];
                    $courseItem->name_en = $inputs['name_en'];
                    $courseItem->type = $inputs['type'];

                    if($courseItem->type == CourseItem::TYPE_TEXT_DB)
                    {
                        $courseItem->content = $inputs['content'];
                        $courseItem->content_en = $inputs['content_en'];
                        $courseItem->video_length = null;
                    }
                    else
                    {
                        $courseItem->content = $inputs['video_path'];
                        $courseItem->content_en = $inputs['video_path_en'];

                        $ffprobe = FFProbe::create([
                            'ffmpeg.binaries'  => 'D:\ffmpeg\bin\ffmpeg.exe',
                            'ffprobe.binaries' => 'D:\ffmpeg\bin\ffprobe.exe',
                        ]);
                        $duration = (int)$ffprobe->format($courseItem->content)->get('duration');
                        if($duration < 1)
                            $duration = null;

                        $courseItem->video_length = $duration;
                    }

                    if($courseItem->video_length != $courseItem->getOriginal('video_length'))
                    {
                        if($courseItem->getOriginal('video_length') && $courseItem->course->video_length)
                            $courseItem->course->video_length -= $courseItem->getOriginal('video_length');

                        if($courseItem->video_length)
                        {
                            if($courseItem->course->video_length)
                                $courseItem->course->video_length += $courseItem->video_length;
                            else
                                $courseItem->course->video_length = $courseItem->video_length;
                        }

                        if($courseItem->course->video_length < 1)
                            $courseItem->course->video_length = null;

                        $courseItem->course->save();
                    }

                    $courseItem->save();

                    DB::commit();

                    return redirect()->action('Backend\CourseController@editCourseItem', ['id' => $courseItem->id])->with('message', 'Success');
                }
                catch(\Exception $e)
                {
                    DB::rollBack();

                    if($create == true)
                        return redirect()->action('Backend\CourseController@createCourseItem', ['id' => $courseItem->course_id])->withErrors(['name' => $e->getMessage()])->withInput();
                    else
                        return redirect()->action('Backend\CourseController@editCourseItem', ['id' => $courseItem->id])->withErrors(['name' => $e->getMessage()])->withInput();
                }
            }
            else
            {
                if($create == true)
                    return redirect()->action('Backend\CourseController@createCourseItem', ['id' => $courseItem->course_id])->withErrors($validator)->withInput();
                else
                    return redirect()->action('Backend\CourseController@editCourseItem', ['id' => $courseItem->id])->withErrors($validator)->withInput();
            }
        }

        if($create == true)
        {
            return view('backend.courses.create_course_item', [
                'courseItem' => $courseItem,
            ]);
        }
        else
        {
            return view('backend.courses.edit_course_item', [
                'courseItem' => $courseItem,
            ]);
        }
    }
}