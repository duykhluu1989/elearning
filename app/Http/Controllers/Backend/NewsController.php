<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Libraries\Widgets\GridView;
use App\Libraries\Helpers\Html;
use App\Libraries\Helpers\Utility;
use App\Models\NewsCategory;

class NewsController extends Controller
{
    public function adminCategory(Request $request)
    {
        $dataProvider = NewsCategory::select('id', 'name', 'status', 'order')->orderBy('id', 'desc');

        $inputs = $request->all();

        if(count($inputs) > 0)
        {

        }

        $dataProvider = $dataProvider->paginate(GridView::ROWS_PER_PAGE);

        $columns = [
            [
                'title' => 'Tên',
                'data' => function($row) {
                    echo Html::a($row->name, [
                        'href' => action('Backend\NewsController@editCategory', ['id' => $row->id]),
                    ]);
                },
            ],
            [
                'title' => 'Thứ Tự',
                'data' => 'order',
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
        ];

        $gridView = new GridView($dataProvider, $columns);
        $gridView->setCheckbox();
        $gridView->setFilters([
            [
                'title' => 'Tình Huống',
                'name' => 'name',
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

        return view('backend.news.admin_category', [
            'gridView' => $gridView,
        ]);
    }

    public function createCategory(Request $request)
    {
        Utility::setBackUrlCookie($request, '/admin/newsCategory?');

        $category = new NewsCategory();
        $category->status = Utility::ACTIVE_DB;
        $category->order = 1;

        return $this->saveCategory($request, $category);
    }

    public function editCategory(Request $request, $id)
    {
        Utility::setBackUrlCookie($request, '/admin/newsCategory?');

        $category = NewsCategory::find($id);

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
                'name' => 'required|unique:news_category,name' . ($create == true ? '' : (',' . $category->id)),
                'name_en' => 'nullable|unique:news_category,name_en' . ($create == true ? '' : (',' . $category->id)),
                'order' => 'required|integer|min:1',
                'slug' => 'nullable|unique:news_category,slug' . ($create == true ? '' : (',' . $category->id)),
                'slug_en' => 'nullable|unique:news_category,slug_en' . ($create == true ? '' : (',' . $category->id)),
            ]);

            if($validator->passes())
            {
                $category->name = $inputs['name'];
                $category->name_en = $inputs['name_en'];
                $category->status = isset($inputs['status']) ? Utility::ACTIVE_DB : Utility::INACTIVE_DB;
                $category->order = $inputs['order'];

                if(empty($inputs['slug']))
                    $category->slug = str_slug($category->name);
                else
                    $category->slug = str_slug($inputs['slug']);

                if(empty($inputs['slug_en']))
                    $category->slug_en = str_slug($category->name_en);
                else
                    $category->slug_en = str_slug($inputs['slug_en']);

                $details = array();
                if(isset($inputs['detail']))
                {
                    foreach($inputs['detail'] as $attribute => $attributeItems)
                    {
                        foreach($attributeItems as $key => $item)
                        {
                            if(!empty($item))
                                $details[$key][$attribute] = $item;
                        }
                    }
                }
                $category->rss = json_encode($details);

                if($create == true)
                    $category->created_at = date('Y-m-d H:i:s');

                $category->save();

                return redirect()->action('Backend\NewsController@editCategory', ['id' => $category->id])->with('messageSuccess', 'Thành Công');
            }
            else
            {
                if($create == true)
                    return redirect()->action('Backend\NewsController@createCategory')->withErrors($validator)->withInput();
                else
                    return redirect()->action('Backend\NewsController@editCategory', ['id' => $category->id])->withErrors($validator)->withInput();
            }
        }

        if($create == true)
        {
            return view('backend.news.create_category', [
                'category' => $category,
            ]);
        }
        else
        {
            return view('backend.news.edit_category', [
                'category' => $category,
            ]);
        }
    }

    public function deleteCategory($id)
    {
        $category = NewsCategory::find($id);

        if(empty($case))
            return view('backend.errors.404');

        $category->delete();

        return redirect(Utility::getBackUrlCookie(action('Backend\NewsController@adminCategory')))->with('messageSuccess', 'Thành Công');
    }

    public function controlDeleteCategory(Request $request)
    {
        $ids = $request->input('ids');

        $categories = NewsCategory::whereIn('id', explode(';', $ids))->get();

        foreach($categories as $category)
            $category->delete();

        if($request->headers->has('referer'))
            return redirect($request->headers->get('referer'))->with('messageSuccess', 'Thành Công');
        else
            return redirect()->action('Backend\NewsController@adminCategory')->with('messageSuccess', 'Thành Công');
    }
}