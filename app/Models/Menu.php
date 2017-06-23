<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Libraries\Helpers\Utility;

class Menu extends Model
{
    const TYPE_CATEGORY_DB = 0;
    const TYPE_STATIC_ARTICLE_DB = 1;
    const TYPE_STATIC_LINK_DB = 2;
    const TYPE_CATEGORY_AUTO_DB = 3;
    const TYPE_CATEGORY_LABEL = 'Chủ Đề';
    const TYPE_STATIC_ARTICLE_LABEL = 'Trang Tĩnh';
    const TYPE_STATIC_LINK_LABEL = 'Đường Dẫn Tĩnh';
    const TYPE_CATEGORY_AUTO_LABEL = 'Chủ Đề Tự Động';

    const TARGET_CATEGORY_DB = 'category';
    const TARGET_STATIC_ARTICLE_DB = 'article';

    protected $table = 'menu';

    public $timestamps = false;

    public function parentMenu()
    {
        return $this->belongsTo('App\Models\Menu', 'parent_id');
    }

    public function childrenMenus()
    {
        return $this->hasMany('App\Models\Menu', 'parent_id');
    }

    public function targetInformation()
    {
        if($this->target == self::TARGET_CATEGORY_DB)
            return $this->belongsTo('App\Models\Category', 'target_id');
        else
            return $this->belongsTo('App\Models\Article', 'target_id');
    }

    public static function getMenuType($value = null)
    {
        $type = [
            self::TYPE_CATEGORY_DB => self::TYPE_CATEGORY_LABEL,
            self::TYPE_STATIC_ARTICLE_DB => self::TYPE_STATIC_ARTICLE_LABEL,
            self::TYPE_STATIC_LINK_DB => self::TYPE_STATIC_LINK_LABEL,
            self::TYPE_CATEGORY_AUTO_DB => self::TYPE_CATEGORY_AUTO_LABEL,
        ];

        if($value !== null && isset($type[$value]))
            return $type[$value];

        return $type;
    }

    public function doDelete()
    {
        $this->delete();

        foreach($this->childrenMenus as $menu)
            $menu->doDelete();
    }

    public function getMenuTitle($backend = true)
    {
        if($backend == true)
        {
            $title = '';

            if(!empty($this->name))
                $title .= $this->name . ' - ';

            if(!empty($this->url))
                $title .= $this->url;
            else if(!empty($this->targetInformation))
                $title .= $this->targetInformation->name;
            else
                $title .= trans('theme.all_category');
        }
        else
        {
            if(!empty($this->name))
                $title = Utility::getValueByLocale($this, 'name');
            else if(!empty($this->targetInformation))
                $title = Utility::getValueByLocale($this->targetInformation, 'name');
            else
                $title = trans('theme.all_category');
        }

        return $title;
    }

    public static function getMenuTree()
    {
        $rootMenus = Menu::select('id', 'name', 'url', 'target_id', 'target', 'type')
            ->whereNull('parent_id')
            ->orderBy('position')
            ->get();

        foreach($rootMenus as $rootMenu)
            $rootMenu->lazyLoadChildrenMenus();

        return $rootMenus;
    }

    public function lazyLoadChildrenMenus()
    {
        $this->load(['childrenMenus' => function($query) {
            $query->select('id', 'parent_id', 'name', 'url', 'target_id', 'target', 'type')->orderBy('position');
        }, 'targetInformation' => function($query) {
            $query->select('id', 'name', 'name_en', 'slug', 'slug_en');
        }]);

        if($this->type == self::TYPE_CATEGORY_AUTO_DB)
        {
            if(empty($this->target_id))
            {
                $categories = Category::select('id', 'name', 'name_en', 'slug', 'slug_en')
                    ->with(['childrenCategories' => function($query) {
                        $query->select('id', 'name', 'name_en', 'slug', 'slug_en', 'parent_id');
                    }])
                    ->where('status', Utility::ACTIVE_DB)
                    ->whereNull('parent_id')
                    ->orderBy('order', 'desc')
                    ->get();
            }
            else
            {
                $categories = Category::select('id', 'name', 'name_en', 'slug', 'slug_en')
                    ->with(['childrenCategories' => function($query) {
                        $query->select('id', 'name', 'name_en', 'slug', 'slug_en', 'parent_id');
                    }])
                    ->where('status', Utility::ACTIVE_DB)
                    ->where('parent_id', $this->target_id)
                    ->orderBy('order', 'desc')
                    ->get();
            }

            $this->auto_categories = $categories;
        }

        if(count($this->childrenMenus) > 0)
        {
            foreach($this->childrenMenus as $childMenu)
                $childMenu->lazyLoadChildrenMenus();
        }
    }

    public function getMenuUrl()
    {
        if(!empty($this->url))
            return $this->url;
        else if(!empty($this->targetInformation))
            return Utility::getValueByLocale($this->targetInformation, 'slug');
        else
            return 'javascript:void(0)';
    }
}