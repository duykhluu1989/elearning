<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Libraries\Widgets\GridView;
use App\Libraries\Helpers\Html;
use App\Libraries\Helpers\Utility;
use App\Models\Article;
use App\Models\Course;

class ArticleController extends Controller
{
    public function adminArticle(Request $request)
    {
        $dataProvider = Article::select('id', 'name', 'status')->orderBy('id', 'desc');

        $inputs = $request->all();

        if(count($inputs) > 0)
        {
            if(!empty($inputs['name']))
                $dataProvider->where('name', 'like', '%' . $inputs['name'] . '%');

            if(isset($inputs['status']) && $inputs['status'] !== '')
                $dataProvider->where('status', $inputs['status']);
        }

        $dataProvider = $dataProvider->paginate(GridView::ROWS_PER_PAGE);

        $columns = [
            [
                'title' => 'Tên',
                'data' => function($row) {
                    echo Html::a($row->name, [
                        'href' => action('Backend\ArticleController@editArticle', ['id' => $row->id]),
                    ]);
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
                'title' => 'Trạng Thái',
                'name' => 'status',
                'type' => 'select',
                'options' => Utility::getTrueFalse(),
            ],
        ]);
        $gridView->setFilterValues($inputs);

        return view('backend.articles.admin_article', [
            'gridView' => $gridView,
        ]);
    }

    public function createArticle(Request $request)
    {
        Utility::setBackUrlCookie($request, '/admin/article?');

        $article = new Article();
        $article->status = Course::STATUS_DRAFT_DB;

        return $this->saveArticle($request, $article);
    }

    public function editArticle(Request $request, $id)
    {
        Utility::setBackUrlCookie($request, '/admin/article?');

        $article = Article::with(['user' => function($query) {
            $query->select('id', 'email');
        }, 'user.profile' => function($query) {
            $query->select('user_id', 'name');
        }])->find($id);

        if(empty($article))
            return view('backend.errors.404');

        return $this->saveArticle($request, $article, false);
    }

    protected function saveArticle($request, $article, $create = true)
    {
        if($create == true)
        {
            return view('backend.articles.create_article', [
                'article' => $article,
            ]);
        }
        else
        {
            return view('backend.articles.edit_article', [
                'article' => $article,
            ]);
        }
    }
}