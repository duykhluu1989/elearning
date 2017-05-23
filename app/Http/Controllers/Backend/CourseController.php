<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Libraries\Widgets\GridView;
use App\Libraries\Helpers\Html;
use App\Libraries\Helpers\Utility;
use App\Models\Category;
use App\Models\Course;

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
                'code' => 'required|unique:category,code' . ($create == true ? '' : (',' . $category->id)),
                'slug' => 'nullable|unique:category,slug' . ($create == true ? '' : (',' . $category->id)),
                'slug_en' => 'nullable|unique:category,slug_en' . ($create == true ? '' : (',' . $category->id)),
                'order' => 'required|integer|min:1',
            ]);

            $validator->after(function($validator) use(&$inputs, $category, $create) {
                if(empty($inputs['parent_name']))
                    $inputs['parent_id'] = null;
                else
                {
                    $parentCategory = Category::where('name', $inputs['parent_name'])->first();

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
                $category->code = $inputs['code'];
                $category->parent_id = $inputs['parent_id'];

                if(empty($inputs['slug']))
                    $category->slug = str_slug($category->name);

                if(empty($inputs['slug_en']))
                    $category->slug_en = str_slug($category->name_en);

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

    public function adminCourse()
    {

    }

    public function createCourse()
    {

    }

    public function editCourse()
    {

    }
}