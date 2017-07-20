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
use App\Models\Tag;
use App\Models\TagCourse;
use App\Models\PromotionPrice;
use App\Models\Discount;
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
                    $status = Utility::getTrueFalse($row->status);
                    if($row->status == Utility::ACTIVE_DB)
                        echo Html::span($status, ['class' => 'label label-success']);
                    else
                        echo Html::span($status, ['class' => 'label label-danger']);
                },
            ],
            [
                'title' => '',
                'data' => function($row) {
                    echo Html::a(Html::i('', ['class' => 'fa fa-flash fa-fw']), [
                        'href' => action('Backend\CourseController@setCategoryPromotionPrice', ['id' => $row->id]),
                        'class' => 'btn btn-primary',
                        'data-container' => 'body',
                        'data-toggle' => 'popover',
                        'data-placement' => 'top',
                        'data-content' => 'Thiết Lập Giá Khuyến Mãi',
                    ]);
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
                'options' => Utility::getTrueFalse(),
            ],
        ]);
        $gridView->setFilterValues($inputs);

        return view('backend.courses.admin_category', [
            'gridView' => $gridView,
        ]);
    }

    public function createCategory(Request $request)
    {
        Utility::setBackUrlCookie($request, '/admin/courseCategory?');

        $category = new Category();
        $category->status = Utility::ACTIVE_DB;
        $category->parent_status = Utility::ACTIVE_DB;
        $category->order = 1;

        return $this->saveCategory($request, $category);
    }

    public function editCategory(Request $request, $id)
    {
        Utility::setBackUrlCookie($request, '/admin/courseCategory?');

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

                            do
                            {
                                if($tempParentCategory->id == $category->id)
                                {
                                    $validator->errors()->add('parent_name', 'Chủ Đề Cha Không Được Là Chủ Đề Con Của Chính Nó');
                                    break;
                                }

                                $tempParentCategory = $tempParentCategory->parentCategory;
                            }
                            while(!empty($tempParentCategory));
                        }

                        $inputs['parent_id'] = $parentCategory->id;
                    }
                }
            });

            if($validator->passes())
            {
                try
                {
                    DB::beginTransaction();

                    $category->name = $inputs['name'];
                    $category->name_en = $inputs['name_en'];
                    $category->status = isset($inputs['status']) ? Utility::ACTIVE_DB : Utility::INACTIVE_DB;
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

                    if($create == true)
                        $category->created_at = date('Y-m-d H:i:s');

                    $category->save();

                    DB::commit();

                    return redirect()->action('Backend\CourseController@editCategory', ['id' => $category->id])->with('messageSuccess', 'Thành Công');
                }
                catch(\Exception $e)
                {
                    DB::rollBack();

                    if($create == true)
                        return redirect()->action('Backend\CourseController@createCategory')->withInput()->with('messageError', $e->getMessage());
                    else
                        return redirect()->action('Backend\CourseController@editCategory', ['id' => $category->id])->withInput()->with('messageError', $e->getMessage());
                }
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

        if(empty($category) || $category->isDeletable() == false)
            return view('backend.errors.404');

        $category->delete();

        return redirect(Utility::getBackUrlCookie(action('Backend\CourseController@adminCategory')))->with('messageSuccess', 'Thành Công');
    }

    public function controlDeleteCategory(Request $request)
    {
        $ids = $request->input('ids');

        $categories = Category::whereIn('id', explode(';', $ids))->get();

        foreach($categories as $category)
        {
            if($category->isDeletable() == true)
                $category->delete();
        }

        if($request->headers->has('referer'))
            return redirect($request->headers->get('referer'))->with('messageSuccess', 'Thành Công');
        else
            return redirect()->action('Backend\CourseController@adminCategory')->with('messageSuccess', 'Thành Công');
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

    public function setCategoryPromotionPrice(Request $request, $id)
    {
        Utility::setBackUrlCookie($request, '/admin/courseCategory?');

        $category = Category::select('id', 'name')->find($id);

        if(empty($category))
            return view('backend.errors.404');

        if($request->isMethod('post'))
        {
            $inputs = $request->all();

            $inputs['value'] = implode('', explode('.', $inputs['value']));

            $validator = Validator::make($inputs, [
                'start_time' => 'required|date',
                'end_time' => 'required|date',
                'value' => 'required|integer|min:1',
                'value_limit' => 'nullable|integer|min:1',
            ]);

            $validator->after(function($validator) use(&$inputs) {
                if($inputs['type'] == Discount::TYPE_PERCENTAGE_DB && $inputs['value'] > 99)
                    $validator->errors()->add('value', 'Phần Trăm Giảm Giá Không Được Lớn Hơn 99');
            });

            if($validator->passes())
            {
                try
                {
                    DB::beginTransaction();

                    $page = 1;

                    do
                    {
                        $courses = Course::select('course.id', 'course.price')
                            ->with('promotionPrice')
                            ->join('category_course', 'course.id', '=', 'category_course.course_id')
                            ->where('category_course.category_id', $category->id)
                            ->paginate(Utility::LARGE_SET_LIMIT, ['*'], 'page', $page);

                        foreach($courses as $course)
                        {
                            if(!empty($course->price))
                            {
                                if($inputs['type'] == Discount::TYPE_PERCENTAGE_DB)
                                {
                                    $discountPrice = round($course->price * $inputs['value'] / 100);
                                    if(!empty($inputs['value_limit']) && $inputs['value_limit'] > $discountPrice)
                                        $discountPrice = $inputs['value_limit'];
                                    $pmPrice = $course->price - $discountPrice;
                                }
                                else
                                    $pmPrice = $course->price - $inputs['value'];

                                if($pmPrice < 0)
                                    $pmPrice = 0;

                                if(empty($course->promotionPrice))
                                {
                                    $promotionPrice = new PromotionPrice();
                                    $promotionPrice->course_id = $course->id;
                                }
                                else
                                    $promotionPrice = $course->promotionPrice;

                                $promotionPrice->status = isset($inputs['status']) ? Utility::ACTIVE_DB : Utility::INACTIVE_DB;
                                $promotionPrice->start_time = $inputs['start_time'];
                                $promotionPrice->end_time = $inputs['end_time'];
                                $promotionPrice->price = $pmPrice;
                                $promotionPrice->save();
                            }
                        }

                        $page ++;

                        $countCourses = count($courses);
                    }
                    while($countCourses > 0);

                    DB::commit();

                    return redirect()->action('Backend\CourseController@setCategoryPromotionPrice', ['id' => $category->id])->withInput()->with('messageSuccess', 'Thành Công');
                }
                catch(\Exception $e)
                {
                    DB::rollBack();

                    return redirect()->action('Backend\CourseController@setCategoryPromotionPrice', ['id' => $category->id])->withInput()->with('messageError', $e->getMessage());
                }
            }
            else
                return redirect()->action('Backend\CourseController@setCategoryPromotionPrice', ['id' => $category->id])->withErrors($validator)->withInput();
        }

        return view('backend.courses.set_category_promotion_price', [
            'category' => $category,
        ]);
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

                return redirect()->action('Backend\CourseController@editLevel', ['id' => $level->id])->with('messageSuccess', 'Thành Công');
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

        if(empty($level) || $level->isDeletable() == false)
            return view('backend.errors.404');

        $level->delete();

        return redirect()->action('Backend\CourseController@adminLevel')->with('messageSuccess', 'Thành Công');
    }

    public function controlDeleteLevel(Request $request)
    {
        $ids = $request->input('ids');

        $levels = Level::whereIn('id', explode(';', $ids))->get();

        foreach($levels as $level)
        {
            if($level->isDeletable() == true)
                $level->delete();
        }

        return redirect()->action('Backend\CourseController@adminLevel')->with('messageSuccess', 'Thành Công');
    }

    public function adminCourse(Request $request)
    {
        $dataProvider = Course::with(['user' => function($query) {
            $query->select('id');
        }, 'user.profile' => function($query) {
            $query->select('user_id', 'name');
        }, 'category' => function($query) {
            $query->select('id', 'name');
        }, 'promotionPrice' => function($query) {
            $query->select('course_id', 'price');
        }])
            ->select('course.id', 'course.name', 'course.user_id', 'course.price', 'course.status', 'course.highlight', 'course.code', 'course.view_count', 'course.bought_count', 'course.category_id')
            ->orderBy('course.id', 'desc');

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

            if(isset($inputs['highlight']) && $inputs['highlight'] !== '')
                $dataProvider->where('course.highlight', $inputs['highlight']);
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
                    echo $row->category->name;
                },
            ],
            [
                'title' => 'Mã',
                'data' => 'code',
            ],
            [
                'title' => 'Giá Tiền',
                'data' => function($row) {
                    echo Utility::formatNumber($row->price) . ' VND';
                },
            ],
            [
                'title' => 'Giá Khuyến Mãi',
                'data' => function($row) {
                    if(!empty($row->promotionPrice))
                        echo Utility::formatNumber($row->promotionPrice->price) . ' VND';
                },
            ],
            [
                'title' => 'Nổi Bật',
                'data' => function($row) {
                    $highlight = Utility::getTrueFalse($row->highlight);
                    if($row->highlight == Utility::ACTIVE_DB)
                        echo Html::span($highlight, ['class' => 'label label-success']);
                    else
                        echo Html::span($highlight, ['class' => 'label label-danger']);
                },
            ],
            [
                'title' => 'Trạng Thái',
                'data' => function($row) {
                    $status = Course::getCourseStatus($row->status);
                    if($row->status == Course::STATUS_PUBLISH_DB)
                        echo Html::span($status, ['class' => 'label label-success']);
                    else if($row->status == Course::STATUS_FINISH_DB)
                        echo Html::span($status, ['class' => 'label label-primary']);
                    else
                        echo Html::span($status, ['class' => 'label label-danger']);
                },
            ],
            [
                'title' => 'Lượt Xem',
                'data' => function($row) {
                    echo Utility::formatNumber($row->view_count);
                },
            ],
            [
                'title' => 'Lượt Mua',
                'data' => function($row) {
                    echo Utility::formatNumber($row->bought_count);
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
            ],
            [
                'title' => '',
                'data' => function($row) {
                    echo Html::a(Html::i('', ['class' => 'fa fa-flash fa-fw']), [
                        'href' => action('Backend\CourseController@setCoursePromotionPrice', ['id' => $row->id]),
                        'class' => 'btn btn-primary',
                        'data-container' => 'body',
                        'data-toggle' => 'popover',
                        'data-placement' => 'top',
                        'data-content' => 'Thiết Lập Giá Khuyến Mãi',
                    ]);
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
                'title' => 'Nổi Bật',
                'name' => 'highlight',
                'type' => 'select',
                'options' => Utility::getTrueFalse(),
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
        Utility::setBackUrlCookie($request, '/admin/course?');

        $course = new Course();
        $course->status = Course::STATUS_DRAFT_DB;
        $course->highlight = Utility::INACTIVE_DB;
        $course->category_status = Utility::ACTIVE_DB;
        $course->price = 0;

        return $this->saveCourse($request, $course);
    }

    public function editCourse(Request $request, $id)
    {
        Utility::setBackUrlCookie($request, '/admin/course?');

        $course = Course::with(['user' => function($query) {
            $query->select('id', 'email');
        }, 'user.profile' => function($query) {
            $query->select('user_id', 'name');
        }, 'categoryCourses' => function($query) {
            $query->orderBy('level');
        }, 'categoryCourses.category' => function($query) {
            $query->select('id', 'name');
        }, 'tagCourses.tag', 'promotionPrice' => function($query) {
            $query->select('id', 'course_id', 'status', 'price');
        }])->find($id);

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

            $validator->after(function($validator) use(&$inputs) {
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

                $category = Category::select('id', 'parent_id', 'status', 'parent_status')->where('name', $inputs['category_name'])->first();

                if(empty($category))
                    $validator->errors()->add('category_name', 'Chủ Đề Không Tồn Tại');
                else
                    $inputs['category'] = $category;
            });

            if($validator->passes())
            {
                try
                {
                    DB::beginTransaction();

                    $course->image = $inputs['image'];
                    $course->user_id = $inputs['user_id'];
                    $course->name = $inputs['name'];
                    $course->name_en = $inputs['name_en'];
                    $course->price = $inputs['price'];
                    $course->status = $inputs['status'];
                    $course->description = $inputs['description'];
                    $course->description_en = $inputs['description_en'];
                    $course->point_price = $inputs['point_price'];
                    $course->code = strtoupper($inputs['code']);

                    if(empty($inputs['level_id']))
                        $course->level_id = null;
                    else
                        $course->level_id = $inputs['level_id'];

                    $course->short_description = $inputs['short_description'];
                    $course->short_description_en = $inputs['short_description_en'];
                    $course->highlight = isset($inputs['highlight']) ? Utility::ACTIVE_DB : Utility::INACTIVE_DB;

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

                    if($create == true)
                        $course->created_at = date('Y-m-d H:i:s');

                    $course->save();

                    if(!empty($course->promotionPrice) && $course->promotionPrice->price >= $course->price)
                    {
                        $course->promotionPrice->status = Utility::INACTIVE_DB;
                        $course->promotionPrice->save();
                    }

                    $course->setCategory($inputs['category']);

                    if(!empty($inputs['tags']))
                    {
                        $tagNames = explode(';', $inputs['tags']);

                        $tags = Tag::whereIn('name', $tagNames)->pluck('name', 'id')->toArray();

                        if(count($tags) < count($tagNames))
                        {
                            foreach($tagNames as $tagName)
                            {
                                if(!empty($tagName))
                                {
                                    $key = array_search($tagName, $tags);

                                    if($key === false)
                                    {
                                        $newTag = new Tag();
                                        $newTag->name = $tagName;
                                        $newTag->save();

                                        $tags[$newTag->id] = $newTag->name;
                                    }
                                }
                            }
                        }

                        foreach($course->tagCourses as $tagCourse)
                        {
                            if(isset($tags[$tagCourse->tag_id]))
                                unset($tags[$tagCourse->tag_id]);
                            else
                                $tagCourse->delete();
                        }

                        foreach($tags as $tagId => $tag)
                        {
                            $tagCourse = new TagCourse();
                            $tagCourse->tag_id = $tagId;
                            $tagCourse->course_id = $course->id;
                            $tagCourse->save();
                        }
                    }

                    DB::commit();

                    return redirect()->action('Backend\CourseController@editCourse', ['id' => $course->id])->with('messageSuccess', 'Thành Công');
                }
                catch(\Exception $e)
                {
                    DB::rollBack();

                    if($create == true)
                        return redirect()->action('Backend\CourseController@createCourse')->withInput()->with('messageError', $e->getMessage());
                    else
                        return redirect()->action('Backend\CourseController@editCourse', ['id' => $course->id])->withInput()->with('messageError', $e->getMessage());
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

    public function deleteCourse($id)
    {
        $course = Course::with('categoryCourses', 'courseItems', 'tagCourses', 'promotionPrice')->find($id);

        if(empty($course) || $course->isDeletable() == false)
            return view('backend.errors.404');

        try
        {
            DB::beginTransaction();

            $course->doDelete();

            DB::commit();
        }
        catch(\Exception $e)
        {
            DB::rollBack();

            return redirect()->action('Backend\CourseController@editCourse', ['id' => $course->id])->with('messageError', $e->getMessage());
        }

        return redirect(Utility::getBackUrlCookie(action('Backend\CourseController@adminCourse')))->with('messageSuccess', 'Thành Công');
    }

    public function controlDeleteCourse(Request $request)
    {
        $ids = $request->input('ids');

        $courses = Course::with('categoryCourses', 'courseItems', 'tagCourses', 'promotionPrice')->whereIn('id', explode(';', $ids))->get();

        foreach($courses as $course)
        {
            if($course->isDeletable() == true)
            {
                try
                {
                    DB::beginTransaction();

                    $course->doDelete();

                    DB::commit();
                }
                catch(\Exception $e)
                {
                    DB::rollBack();

                    return redirect()->action('Backend\CourseController@adminCourse')->with('messageError', $e->getMessage());
                }
            }
        }

        if($request->headers->has('referer'))
            return redirect($request->headers->get('referer'))->with('messageSuccess', 'Thành Công');
        else
            return redirect()->action('Backend\CourseController@adminCourse')->with('messageSuccess', 'Thành Công');
    }

    public function autoCompleteCourse(Request $request)
    {
        $term = $request->input('term');

        $builder = Course::select('id', 'name')->where('name', 'like', '%' . $term . '%')->limit(Utility::AUTO_COMPLETE_LIMIT);

        $courses = $builder->get()->toJson();

        return $courses;
    }

    public function setCoursePromotionPrice(Request $request, $id)
    {
        Utility::setBackUrlCookie($request, '/admin/course?');

        $course = Course::with('promotionPrice')->select('id', 'name', 'price')->find($id);

        if(empty($course))
            return view('backend.errors.404');

        if(empty($course->promotionPrice))
        {
            $promotionPrice = new PromotionPrice();
            $promotionPrice->course_id = $course->id;
            $promotionPrice->status = Utility::ACTIVE_DB;
            $promotionPrice->price = 0;

            $promotionPrice->course()->associate($course);
        }
        else
            $promotionPrice = $course->promotionPrice;

        if($request->isMethod('post'))
        {
            $inputs = $request->all();

            $inputs['price'] = implode('', explode('.', $inputs['price']));

            $validator = Validator::make($inputs, [
                'start_time' => 'required|date',
                'end_time' => 'required|date',
                'price' => 'required|integer|min:0',
            ]);

            $validator->after(function($validator) use(&$inputs, $promotionPrice) {
                if($inputs['price'] >= $promotionPrice->course->price)
                    $validator->errors()->add('price', 'Giá Khuyến Mãi Phải Thấp Hơn Giá Tiền Khóa Học');
            });

            if($validator->passes())
            {
                $promotionPrice->status = isset($inputs['status']) ? Utility::ACTIVE_DB : Utility::INACTIVE_DB;
                $promotionPrice->start_time = $inputs['start_time'];
                $promotionPrice->end_time = $inputs['end_time'];
                $promotionPrice->price = $inputs['price'];
                $promotionPrice->save();

                return redirect()->action('Backend\CourseController@setCoursePromotionPrice', ['id' => $course->id])->with('messageSuccess', 'Thành Công');
            }
            else
                return redirect()->action('Backend\CourseController@setCoursePromotionPrice', ['id' => $course->id])->withErrors($validator)->withInput();
        }

        return view('backend.courses.set_course_promotion_price', [
            'course' => $course,
            'promotionPrice' => $promotionPrice,
        ]);
    }

    public function adminCourseItem(Request $request, $id)
    {
        Utility::setBackUrlCookie($request, '/admin/course?');

        $course = Course::select('id', 'name', 'video_length', 'item_count', 'audio_length')->with(['courseItems' => function($query) {
            $query->select('id', 'course_id', 'name', 'type', 'number', 'video_length', 'audio_length')->orderBy('number');
        }])->find($id);

        if(empty($course))
            return view('backend.errors.404');

        $dataProvider = $course->courseItems;

        $columns = [
            [
                'title' => 'Tổng Số Bài Học: ' . $course->item_count,
                'data' => function($row) {
                    echo 'Bài Học Số ' . $row->number;
                },
            ],
            [
                'title' => 'Tên',
                'data' => function($row) {
                    echo Html::a($row->name, [
                        'href' => action('Backend\CourseController@editCourseItem', ['id' => $row->id]),
                    ]);
                },
            ],
            [
                'title' => 'Loại',
                'data' => function($row) {
                    $type = CourseItem::getCourseItemType($row->type);
                    if($row->type == CourseItem::TYPE_TEXT_DB)
                        $iconHtml = Html::i('', ['class' => 'fa fa-file-text-o fa-fw']);
                    else if($row->type == CourseItem::TYPE_VIDEO_DB)
                        $iconHtml = Html::i('', ['class' => 'fa fa-youtube-play fa-fw']);
                    else
                        $iconHtml = Html::i('', ['class' => 'fa fa-volume-up fa-fw']);
                    echo $iconHtml . ' ' . $type;
                },
            ],
            [
                'title' => 'Tổng Thời Gian Video: ' . Utility::formatTimeString($course->video_length) . ' | Tổng Thời Gian Audio: ' .  Utility::formatTimeString($course->audio_length),
                'data' => function($row) {
                    if($row->type == CourseItem::TYPE_VIDEO_DB)
                        echo Utility::formatTimeString($row->video_length);
                    else
                        echo Utility::formatTimeString($row->audio_length);
                },
            ],
        ];

        $gridView = new GridView($dataProvider, $columns);
        $gridView->setCheckbox();
        $gridView->unsetPagination();

        return view('backend.courses.admin_course_item', [
            'course' => $course,
            'gridView' => $gridView,
        ]);
    }

    public function createCourseItem(Request $request, $id)
    {
        $course = Course::select('id', 'name', 'video_length', 'item_count', 'audio_length')->find($id);

        if(empty($course))
            return view('backend.errors.404');

        $courseItem = new CourseItem();
        $courseItem->course_id = $course->id;
        $courseItem->type = CourseItem::TYPE_TEXT_DB;
        $courseItem->number = $course->item_count + 1;

        $courseItem->course()->associate($course);

        return $this->saveCourseItem($request, $courseItem);
    }

    public function editCourseItem(Request $request, $id)
    {
        $courseItem = CourseItem::with(['course' => function($query) {
            $query->select('id', 'name', 'video_length', 'item_count', 'audio_length');
        }])->find($id);

        if(empty($courseItem))
            return view('backend.errors.404');

        return $this->saveCourseItem($request, $courseItem, false);
    }

    protected function saveCourseItem($request, $courseItem, $create = true)
    {
        if($request->isMethod('post'))
        {
            $inputs = $request->all();

            $validator = Validator::make($inputs, [
                'name' => 'required',
                'content' => 'required_if:type,' . CourseItem::TYPE_TEXT_DB,
                'file_path' => 'required_if:type,' . CourseItem::TYPE_VIDEO_DB . ',' . CourseItem::TYPE_AUDIO_DB,
            ]);

            $validator->after(function($validator) use(&$inputs) {
                if($inputs['type'] != CourseItem::TYPE_TEXT_DB)
                {
                    if(!file_exists($inputs['file_path']) || !is_file($inputs['file_path']))
                        $validator->errors()->add('file_path', 'Đường Dẫn File Không Đúng');

                    if(!empty($inputs['file_path_en']))
                    {
                        if(file_exists($inputs['file_path_en']) && is_file($inputs['file_path_en']))
                            $validator->errors()->add('file_path_en', 'Đường Dẫn File EN Không Đúng');
                    }
                }
            });

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
                        $courseItem->audio_length = null;
                    }
                    else
                    {
                        $courseItem->content = $inputs['file_path'];
                        $courseItem->content_en = $inputs['file_path_en'];

                        $ffprobe = FFProbe::create();
                        $duration = (int)$ffprobe->format($courseItem->content)->get('duration');
                        if($duration < 1)
                            $duration = null;

                        if($courseItem->type == CourseItem::TYPE_VIDEO_DB)
                        {
                            $courseItem->video_length = $duration;
                            $courseItem->audio_length = null;
                        }
                        else
                        {
                            $courseItem->audio_length = $duration;
                            $courseItem->video_length = null;
                        }
                    }

                    if($create == true)
                        $courseItem->course->item_count += 1;

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
                    }

                    if($courseItem->audio_length != $courseItem->getOriginal('audio_length'))
                    {
                        if($courseItem->getOriginal('audio_length') && $courseItem->course->audio_length)
                            $courseItem->course->audio_length -= $courseItem->getOriginal('audio_length');

                        if($courseItem->audio_length)
                        {
                            if($courseItem->course->audio_length)
                                $courseItem->course->audio_length += $courseItem->audio_length;
                            else
                                $courseItem->course->audio_length = $courseItem->audio_length;
                        }

                        if($courseItem->course->audio_length < 1)
                            $courseItem->course->audio_length = null;
                    }

                    $courseItem->course->save();

                    $courseItem->save();

                    DB::commit();

                    return redirect()->action('Backend\CourseController@editCourseItem', ['id' => $courseItem->id])->with('messageSuccess', 'Thành Công');
                }
                catch(\Exception $e)
                {
                    DB::rollBack();

                    if($create == true)
                        return redirect()->action('Backend\CourseController@createCourseItem', ['id' => $courseItem->course_id])->withInput()->with('messageError', $e->getMessage());
                    else
                        return redirect()->action('Backend\CourseController@editCourseItem', ['id' => $courseItem->id])->withInput()->with('messageError', $e->getMessage());
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

    public function deleteCourseItem($id)
    {
        $courseItem = CourseItem::with('course')->find($id);

        if(empty($courseItem) || $courseItem->isDeletable() == false)
            return view('backend.errors.404');

        try
        {
            DB::beginTransaction();

            $courseItem->delete();

            if($courseItem->video_length)
            {
                $courseItem->course->video_length -= $courseItem->video_length;

                if($courseItem->course->video_length < 1)
                    $courseItem->course->video_length = null;
            }

            if($courseItem->audio_length)
            {
                $courseItem->course->audio_length -= $courseItem->audio_length;

                if($courseItem->course->audio_length < 1)
                    $courseItem->course->audio_length = null;
            }

            $courseItem->course->item_count -= 1;

            $courseItem->course->save();

            $higherNumberCourseItems = CourseItem::where('course_id', $courseItem->course_id)
                ->where('number', '>', $courseItem->number)
                ->orderBy('number')
                ->get();

            foreach($higherNumberCourseItems as $higherNumberCourseItem)
            {
                $higherNumberCourseItem->number -= 1;
                $higherNumberCourseItem->save();
            }

            DB::commit();
        }
        catch(\Exception $e)
        {
            DB::rollBack();

            return redirect()->action('Backend\CourseController@editCourseItem', ['id' => $courseItem->id])->with('messageError', $e->getMessage());
        }

        return redirect()->action('Backend\CourseController@adminCourseItem', ['id' => $courseItem->course->id])->with('messageSuccess', 'Thành Công');
    }

    public function controlDeleteCourseItem(Request $request)
    {
        $ids = $request->input('ids');
        $ids = explode(';', $ids);

        $course = Course::select('course.id', 'course.item_count', 'course.video_length', 'course.audio_length')
            ->with(['courseItems' => function($query) {
                $query->orderBy('number');
            }])
            ->join('course_item', 'course.id', '=', 'course_item.course_id')
            ->whereIn('course_item.id', $ids)
            ->first();

        try
        {
            DB::beginTransaction();

            foreach($course->courseItems as $key => $courseItem)
            {
                if(in_array($courseItem->id, $ids) && $courseItem->isDeletable() == true)
                {
                    $courseItem->delete();

                    if($courseItem->video_length && !empty($course->video_length))
                    {
                        $course->video_length -= $courseItem->video_length;

                        if($course->video_length < 1)
                            $course->video_length = null;
                    }

                    if($courseItem->audio_length && !empty($course->audio_length))
                    {
                        $course->audio_length -= $courseItem->audio_length;

                        if($course->audio_length < 1)
                            $course->audio_length = null;
                    }

                    $course->item_count -= 1;

                    unset($course->courseItems[$key]);
                }
            }

            $course->save();

            $number = 1;

            foreach($course->courseItems as $courseItem)
            {
                if($courseItem->number != $number)
                {
                    $courseItem->number = $number;
                    $courseItem->save();
                }

                $number ++;
            }

            DB::commit();
        }
        catch(\Exception $e)
        {
            DB::rollBack();

            return redirect()->action('Backend\CourseController@adminCourseItem', ['id' => $course->id])->with('messageError', $e->getMessage());
        }

        return redirect()->action('Backend\CourseController@adminCourseItem', ['id' => $course->id])->with('messageSuccess', 'Thành Công');
    }

    public function adminTag(Request $request)
    {
        $dataProvider = Tag::select('id', 'name')->orderBy('id', 'desc');

        $inputs = $request->all();

        if(count($inputs) > 0)
        {
            if(!empty($inputs['name']))
                $dataProvider->where('name', 'like', '%' . $inputs['name'] . '%');
        }

        $dataProvider = $dataProvider->paginate(GridView::ROWS_PER_PAGE);

        $columns = [
            [
                'title' => 'Tên',
                'data' => function($row) {
                    echo Html::a($row->name, [
                        'href' => action('Backend\CourseController@editTag', ['id' => $row->id]),
                    ]);
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
        ]);
        $gridView->setFilterValues($inputs);

        return view('backend.courses.admin_tag', [
            'gridView' => $gridView,
        ]);
    }

    public function createTag(Request $request)
    {
        Utility::setBackUrlCookie($request, '/admin/courseTag?');

        $tag = new Tag();

        return $this->saveTag($request, $tag);
    }

    public function editTag(Request $request, $id)
    {
        Utility::setBackUrlCookie($request, '/admin/courseTag?');

        $tag = Tag::find($id);

        if(empty($tag))
            return view('backend.errors.404');

        return $this->saveTag($request, $tag, false);
    }

    protected function saveTag($request, $tag, $create = true)
    {
        if($request->isMethod('post'))
        {
            $inputs = $request->all();

            $validator = Validator::make($inputs, [
                'name' => 'required|unique:tag,name' . ($create == true ? '' : (',' . $tag->id)),
            ]);

            if($validator->passes())
            {
                $tag->name = strtolower($inputs['name']);
                $tag->save();

                return redirect()->action('Backend\CourseController@editTag', ['id' => $tag->id])->with('messageSuccess', 'Thành Công');
            }
            else
            {
                if($create == true)
                    return redirect()->action('Backend\CourseController@createTag')->withErrors($validator)->withInput();
                else
                    return redirect()->action('Backend\CourseController@editTag', ['id' => $tag->id])->withErrors($validator)->withInput();
            }
        }

        if($create == true)
        {
            return view('backend.courses.create_tag', [
                'tag' => $tag,
            ]);
        }
        else
        {
            return view('backend.courses.edit_tag', [
                'tag' => $tag,
            ]);
        }
    }

    public function deleteTag($id)
    {
        $tag = Tag::find($id);

        if(empty($tag) || $tag->isDeletable() == false)
            return view('backend.errors.404');

        $tag->delete();

        return redirect(Utility::getBackUrlCookie(action('Backend\CourseController@adminTag')))->with('messageSuccess', 'Thành Công');
    }

    public function controlDeleteTag(Request $request)
    {
        $ids = $request->input('ids');

        $tags = Tag::whereIn('id', explode(';', $ids))->get();

        foreach($tags as $tag)
        {
            if($tag->isDeletable() == true)
                $tag->delete();
        }

        if($request->headers->has('referer'))
            return redirect($request->headers->get('referer'))->with('messageSuccess', 'Thành Công');
        else
            return redirect()->action('Backend\CourseController@adminTag')->with('messageSuccess', 'Thành Công');
    }

    public function autoCompleteTag(Request $request)
    {
        $term = $request->input('term');

        $tags = Tag::where('name', 'like', '%' . $term . '%')->limit(Utility::AUTO_COMPLETE_LIMIT)->pluck('name')->toJson();

        return $tags;
    }
}