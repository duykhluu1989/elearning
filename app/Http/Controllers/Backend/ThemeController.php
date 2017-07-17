<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Menu;
use App\Models\Category;
use App\Models\Article;

class ThemeController extends Controller
{
    public function adminMenu(Request $request)
    {
        if($request->isMethod('post'))
        {
            $inputs = $request->all();

            $validator = Validator::make($inputs, [
                'parent_id' => 'required|array',
            ]);

            if($validator->passes())
            {
                try
                {
                    DB::beginTransaction();

                    $i = 1;

                    foreach($inputs['parent_id'] as $id => $parentId)
                    {
                        if(empty($parentId))
                            $parentId = 'NULL';

                        DB::statement('
                            UPDATE `menu`
                            SET `position` = ' . $i . ', `parent_id` = ' . $parentId . '
                            WHERE `id` = ' . $id
                        );

                        $i ++;
                    }

                    DB::commit();

                    return redirect()->action('Backend\ThemeController@adminMenu')->with('messageSuccess', 'Thành Công');
                }
                catch(\Exception $e)
                {
                    DB::rollBack();

                    return redirect()->action('Backend\ThemeController@adminMenu')->with('messageError', $e->getMessage());
                }
            }
        }

        $rootMenus = Menu::getMenuTree();

        $menu = new Menu();
        $menu->type = Menu::TYPE_CATEGORY_DB;

        return view('backend.themes.admin_menu', [
            'rootMenus' => $rootMenus,
            'menu' => $menu,
        ]);
    }

    public function createMenu(Request $request)
    {
        $menu = new Menu();
        $menu->type = Menu::TYPE_CATEGORY_DB;
        $menu->position = Menu::max('position') + 1;

        return $this->saveMenu($request, $menu);
    }

    public function editMenu(Request $request, $id)
    {
        if($request->isMethod('get') && $request->ajax() == false)
            return view('backend.errors.404');

        $menu = Menu::find($id);

        if(empty($menu))
            return '';

        return $this->saveMenu($request, $menu, false);
    }

    protected function saveMenu($request, $menu, $create = true)
    {
        if($request->isMethod('post'))
        {
            $inputs = $request->all();

            $validator = Validator::make($inputs, [
                'name' => 'required_if:type,' . Menu::TYPE_STATIC_LINK_DB,
                'url' => 'nullable|url|required_if:type,' . Menu::TYPE_STATIC_LINK_DB,
                'target_name' => 'required_if:type,' . Menu::TYPE_CATEGORY_DB . ',' . Menu::TYPE_STATIC_ARTICLE_DB,
            ]);

            $validator->after(function($validator) use(&$inputs) {
                if($inputs['type'] == Menu::TYPE_CATEGORY_DB || $inputs['type'] == Menu::TYPE_CATEGORY_AUTO_DB)
                {
                    if(!empty($inputs['target_name']))
                    {
                        $category = Category::select('id')->where('name', $inputs['target_name'])->first();

                        if(empty($category))
                            $validator->errors()->add('target_name', 'Chủ Đề Không Tồn Tại');
                        else
                        {
                            $inputs['target'] = Menu::TARGET_CATEGORY_DB;
                            $inputs['target_id'] = $category->id;
                        }
                    }
                }
                else if($inputs['type'] == Menu::TYPE_STATIC_ARTICLE_DB)
                {
                    $article = Article::select('id')->where('name', $inputs['target_name'])->where('type', Article::TYPE_STATIC_ARTICLE_DB)->first();

                    if(empty($article))
                        $validator->errors()->add('target_name', 'Trang Tĩnh Không Tồn Tại');
                    else
                    {
                        $inputs['target'] = Menu::TARGET_STATIC_ARTICLE_DB;
                        $inputs['target_id'] = $article->id;
                    }
                }
            });


            if($validator->passes())
            {
                $menu->name = $inputs['name'];
                $menu->name_en = $inputs['name_en'];
                $menu->url = $inputs['url'];
                $menu->type = $inputs['type'];

                if(!empty($inputs['target']))
                {
                    $menu->target = $inputs['target'];
                    $menu->target_id = $inputs['target_id'];
                }
                else
                {
                    $menu->target = null;
                    $menu->target_id = null;
                }

                $menu->save();

                return view('backend.themes.partials.get_menu', [
                    'menu' => $menu,
                    'create' => $create,
                ]);
            }
            else
                return view('backend.themes.partials.menu_form', [
                    'menu' => $menu,
                ])->withErrors($validator);
        }

        return view('backend.themes.partials.menu_form', [
            'menu' => $menu,
        ]);
    }

    public function deleteMenu(Request $request, $id)
    {
        if($request->ajax() == false)
            return view('backend.errors.404');

        $menu = Menu::find($id);

        if(empty($menu))
            return '';

        try
        {
            DB::beginTransaction();

            $menu->doDelete();

            DB::commit();

            return 'success';
        }
        catch(\Exception $e)
        {
            DB::rollBack();

            return '';
        }
    }
}