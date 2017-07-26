<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Libraries\Widgets\GridView;
use App\Libraries\Helpers\Html;
use App\Libraries\Helpers\Utility;
use App\Models\Article;
use App\Models\Course;
use App\Models\User;

class ArticleController extends Controller
{
    public function adminArticle(Request $request)
    {
        $dataProvider = Article::with(['user' => function($query) {
            $query->select('id');
        }, 'user.profile' => function($query) {
            $query->select('user_id', 'name');
        }])->select('article.id', 'article.name', 'article.status', 'article.user_id', 'article.view_count')
            ->where('article.type', Article::TYPE_EXPERT_ARTICLE_DB)
            ->orderBy('article.id', 'desc');

        $inputs = $request->all();

        if(count($inputs) > 0)
        {
            if(!empty($inputs['name']))
                $dataProvider->where('article.name', 'like', '%' . $inputs['name'] . '%');

            if(!empty($inputs['user_name']))
            {
                $dataProvider->join('user', 'course.user_id', '=', 'user.id')
                    ->join('profile', 'user.id', '=', 'profile.user_id')
                    ->where('profile.name', 'like', '%' . $inputs['user_name'] . '%');
            }

            if(isset($inputs['status']) && $inputs['status'] !== '')
                $dataProvider->where('article.status', $inputs['status']);
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
                'title' => 'Chuyên Gia',
                'data' => function($row) {
                    echo $row->user->profile->name;
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
                'title' => 'Chuyên Gia',
                'name' => 'user_name',
                'type' => 'input',
            ],
            [
                'title' => 'Trạng Thái',
                'name' => 'status',
                'type' => 'select',
                'options' => Course::getCourseStatus(),
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
        $article->type = Article::TYPE_EXPERT_ARTICLE_DB;
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
        }])->where('type', Article::TYPE_EXPERT_ARTICLE_DB)->find($id);

        if(empty($article))
            return view('backend.errors.404');

        return $this->saveArticle($request, $article, false);
    }

    protected function saveArticle($request, $article, $create = true)
    {
        if($request->isMethod('post'))
        {
            $inputs = $request->all();

            $validator = Validator::make($inputs, [
                'user_name' => 'required',
                'name' => 'required|unique:article,name' . ($create == true ? '' : (',' . $article->id)),
                'name_en' => 'nullable|unique:article,name_en' . ($create == true ? '' : (',' . $article->id)),
                'content' => 'required',
                'slug' => 'nullable|unique:article,slug' . ($create == true ? '' : (',' . $article->id)),
                'slug_en' => 'nullable|unique:article,slug_en' . ($create == true ? '' : (',' . $article->id)),
            ]);

            $validator->after(function($validator) use(&$inputs) {
                $expertNameParts = explode(' - ', $inputs['user_name']);

                if(count($expertNameParts) == 2)
                {
                    $user = User::select('user.id')
                        ->join('profile', 'user.id', '=', 'profile.user_id')
                        ->where('user.email', $expertNameParts[1])
                        ->where('profile.name', $expertNameParts[0])
                        ->first();

                    if(!empty($user))
                        $inputs['user_id'] = $user->id;
                }

                if(!isset($inputs['user_id']))
                    $validator->errors()->add('user_name', 'Chuyên Gia Không Tồn Tại');
            });

            if($validator->passes())
            {
                $article->image = $inputs['image'];
                $article->user_id = $inputs['user_id'];
                $article->name = $inputs['name'];
                $article->name_en = $inputs['name_en'];
                $article->status = $inputs['status'];
                $article->content = $inputs['content'];
                $article->content_en = $inputs['content_en'];
                $article->short_description = $inputs['short_description'];
                $article->short_description_en = $inputs['short_description_en'];

                if(empty($article->published_at) && $article->status == Course::STATUS_PUBLISH_DB)
                    $article->published_at = date('Y-m-d H:i:s');

                if(empty($inputs['slug']))
                    $article->slug = str_slug($article->name);
                else
                    $article->slug = str_slug($inputs['slug']);

                if(empty($inputs['slug_en']))
                    $article->slug_en = str_slug($article->name_en);
                else
                    $article->slug_en = str_slug($inputs['slug_en']);

                if($create == true)
                    $article->created_at = date('Y-m-d H:i:s');

                $article->save();

                return redirect()->action('Backend\ArticleController@editArticle', ['id' => $article->id])->with('messageSuccess', 'Thành Công');
            }
            else
            {
                if($create == true)
                    return redirect()->action('Backend\ArticleController@createArticle')->withErrors($validator)->withInput();
                else
                    return redirect()->action('Backend\ArticleController@editArticle', ['id' => $article->id])->withErrors($validator)->withInput();
            }
        }

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

    public function deleteArticle($id)
    {
        $article = Article::where('type', Article::TYPE_EXPERT_ARTICLE_DB)->find($id);

        if(empty($article))
            return view('backend.errors.404');

        $article->delete();

        return redirect(Utility::getBackUrlCookie(action('Backend\ArticleController@adminArticle')))->with('messageSuccess', 'Thành Công');
    }

    public function controlDeleteArticle(Request $request)
    {
        $ids = $request->input('ids');

        $articles = Article::whereIn('id', explode(';', $ids))->where('type', Article::TYPE_EXPERT_ARTICLE_DB)->get();

        foreach($articles as $article)
            $article->delete();

        if($request->headers->has('referer'))
            return redirect($request->headers->get('referer'))->with('messageSuccess', 'Thành Công');
        else
            return redirect()->action('Backend\ArticleController@adminArticle')->with('messageSuccess', 'Thành Công');
    }

    public function adminArticleStatic(Request $request)
    {
        $dataProvider = Article::select('id', 'name', 'status', 'view_count', 'order', 'group')
            ->where('type', Article::TYPE_STATIC_ARTICLE_DB)
            ->orderBy('id', 'desc');

        $inputs = $request->all();

        if(count($inputs) > 0)
        {
            if(!empty($inputs['name']))
                $dataProvider->where('name', 'like', '%' . $inputs['name'] . '%');

            if(isset($inputs['status']) && $inputs['status'] !== '')
                $dataProvider->where('status', $inputs['status']);

            if(isset($inputs['group']) && $inputs['group'] !== '')
                $dataProvider->where('group', $inputs['group']);
        }

        $dataProvider = $dataProvider->paginate(GridView::ROWS_PER_PAGE);

        $columns = [
            [
                'title' => 'Tên',
                'data' => function($row) {
                    echo Html::a($row->name, [
                        'href' => action('Backend\ArticleController@editArticleStatic', ['id' => $row->id]),
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
                'title' => 'Nhóm Trang',
                'data' => function($row) {
                    if($row->group !== null)
                        echo Article::getStaticArticleGroup($row->group);
                },
            ],
            [
                'title' => 'Lượt Xem',
                'data' => function($row) {
                    echo Utility::formatNumber($row->view_count);
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
                'options' => Course::getCourseStatus(),
            ],
            [
                'title' => 'Nhóm Trang',
                'name' => 'group',
                'type' => 'select',
                'options' => Article::getStaticArticleGroup(),
            ],
        ]);
        $gridView->setFilterValues($inputs);

        return view('backend.articles.admin_article_static', [
            'gridView' => $gridView,
        ]);
    }

    public function createArticleStatic(Request $request)
    {
        Utility::setBackUrlCookie($request, '/admin/articleStatic?');

        $article = new Article();
        $article->type = Article::TYPE_STATIC_ARTICLE_DB;
        $article->status = Course::STATUS_DRAFT_DB;
        $article->group = null;
        $article->order = 1;

        return $this->saveArticleStatic($request, $article);
    }

    public function editArticleStatic(Request $request, $id)
    {
        Utility::setBackUrlCookie($request, '/admin/articleStatic?');

        $article = Article::where('type', Article::TYPE_STATIC_ARTICLE_DB)->find($id);

        if(empty($article))
            return view('backend.errors.404');

        return $this->saveArticleStatic($request, $article, false);
    }

    protected function saveArticleStatic($request, $article, $create = true)
    {
        if($request->isMethod('post'))
        {
            $inputs = $request->all();

            $validator = Validator::make($inputs, [
                'name' => 'required|unique:article,name' . ($create == true ? '' : (',' . $article->id)),
                'name_en' => 'nullable|unique:article,name_en' . ($create == true ? '' : (',' . $article->id)),
                'content' => 'required_unless:group,' . Article::STATIC_ARTICLE_GROUP_FAQ_DB,
                'detail' => 'required_if:group,' . Article::STATIC_ARTICLE_GROUP_FAQ_DB,
                'slug' => 'nullable|unique:article,slug' . ($create == true ? '' : (',' . $article->id)),
                'slug_en' => 'nullable|unique:article,slug_en' . ($create == true ? '' : (',' . $article->id)),
                'order' => 'required|integer|min:1',
            ]);

            if($validator->passes())
            {
                $article->image = $inputs['image'];
                $article->name = $inputs['name'];
                $article->name_en = $inputs['name_en'];
                $article->status = $inputs['status'];
                $article->short_description = $inputs['short_description'];
                $article->short_description_en = $inputs['short_description_en'];
                $article->order = $inputs['order'];

                if(isset($inputs['group']) && $inputs['group'] !== '')
                    $article->group = $inputs['group'];
                else
                    $article->group = null;

                if(empty($inputs['slug']))
                    $article->slug = str_slug($article->name);
                else
                    $article->slug = str_slug($inputs['slug']);

                if(empty($inputs['slug_en']))
                    $article->slug_en = str_slug($article->name_en);
                else
                    $article->slug_en = str_slug($inputs['slug_en']);

                if($create == true)
                    $article->created_at = date('Y-m-d H:i:s');

                if(isset($inputs['detail']))
                {
                    $details = array();

                    foreach($inputs['detail'] as $attribute => $attributeItems)
                    {
                        foreach($attributeItems as $key => $item)
                        {
                            if(!empty($item))
                                $details[$key][$attribute] = $item;
                        }
                    }

                    $article->content = json_encode($details);
                }
                else
                    $article->content = $inputs['content'];

                if(isset($inputs['detail_en']))
                {
                    $details = array();

                    foreach($inputs['detail_en'] as $attribute => $attributeItems)
                    {
                        foreach($attributeItems as $key => $item)
                        {
                            if(!empty($item))
                                $details[$key][$attribute] = $item;
                        }
                    }

                    $article->content_en = json_encode($details);
                }
                else
                    $article->content_en = $inputs['content_en'];

                $article->save();

                return redirect()->action('Backend\ArticleController@editArticleStatic', ['id' => $article->id])->with('messageSuccess', 'Thành Công');
            }
            else
            {
                if($create == true)
                    return redirect()->action('Backend\ArticleController@createArticleStatic')->withErrors($validator)->withInput();
                else
                    return redirect()->action('Backend\ArticleController@editArticleStatic', ['id' => $article->id])->withErrors($validator)->withInput();
            }
        }

        if($create == true)
        {
            return view('backend.articles.create_article_static', [
                'article' => $article,
            ]);
        }
        else
        {
            return view('backend.articles.edit_article_static', [
                'article' => $article,
            ]);
        }
    }

    public function autoCompleteArticleStatic(Request $request)
    {
        $term = $request->input('term');

        $builder = Article::select('id', 'name')->where('name', 'like', '%' . $term . '%')->where('type', Article::TYPE_STATIC_ARTICLE_DB)->limit(Utility::AUTO_COMPLETE_LIMIT);

        $articles = $builder->get()->toJson();

        return $articles;
    }

    public function deleteArticleStatic($id)
    {
        $article = Article::where('type', Article::TYPE_STATIC_ARTICLE_DB)->find($id);

        if(empty($article))
            return view('backend.errors.404');

        $article->delete();

        return redirect(Utility::getBackUrlCookie(action('Backend\ArticleController@adminArticleStatic')))->with('messageSuccess', 'Thành Công');
    }

    public function controlDeleteArticleStatic(Request $request)
    {
        $ids = $request->input('ids');

        $articles = Article::whereIn('id', explode(';', $ids))->where('type', Article::TYPE_STATIC_ARTICLE_DB)->get();

        foreach($articles as $article)
            $article->delete();

        if($request->headers->has('referer'))
            return redirect($request->headers->get('referer'))->with('messageSuccess', 'Thành Công');
        else
            return redirect()->action('Backend\ArticleController@adminArticleStatic')->with('messageSuccess', 'Thành Công');
    }
}