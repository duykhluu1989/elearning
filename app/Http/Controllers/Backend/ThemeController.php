<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Menu;

class ThemeController extends Controller
{
    public function adminMenu()
    {
        $menu = new Menu();
        $menu->type = Menu::TYPE_CATEGORY_DB;

        return view('backend.themes.admin_menu', [
            'menu' => $menu,
        ]);
    }

    public function createMenu(Request $request)
    {
        $menu = new Menu();
        $menu->type = Menu::TYPE_CATEGORY_DB;

        return $this->saveMenu($request, $menu);
    }

    public function editMenu(Request $request, $id)
    {
        $menu = Menu::find($id);

        return $this->saveMenu($request, $menu);
    }

    protected function saveMenu($request, $menu)
    {
        return view('backend.themes.partials.menu_form', [
            'menu' => $menu,
        ]);
    }
}