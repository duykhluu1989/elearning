<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

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
        else if($this->target == self::TARGET_STATIC_ARTICLE_DB)
            return $this->belongsTo('App\Models\Article', 'target_id');
        else
            return null;
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

    public function isDeletable()
    {
        return true;
    }

    public function getMenuTitle()
    {
        $title = '';

        if(!empty($this->name))
            $title .= $this->name . ' - ';

        if(!empty($this->url))
            $title .= $this->url;
        else if(!empty($this->targetInformation))
            $title .= $this->targetInformation->name;
        else
            $title .= 'Tất Cả Chủ Đề';

        return $title;
    }
}