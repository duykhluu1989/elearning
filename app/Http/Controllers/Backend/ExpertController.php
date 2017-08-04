<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Libraries\Widgets\GridView;
use App\Libraries\Helpers\Utility;
use App\Models\User;
use App\Models\Expert;
use App\Models\ExpertEvent;

class ExpertController extends Controller
{
    public function controlChangeOnlineExpert(Request $request, $online)
    {
        $ids = $request->input('ids');

        $experts = Expert::whereIn('user_id', explode(';', $ids))->get();

        foreach($experts as $expert)
        {
            if($online == Utility::INACTIVE_DB)
                $expert->online = Utility::INACTIVE_DB;
            else
                $expert->online = Utility::ACTIVE_DB;

            $expert->save();
        }

        if($request->headers->has('referer'))
            return redirect($request->headers->get('referer'))->with('messageSuccess', 'Thành Công');
        else
            return redirect()->action('Backend\UserController@adminUserExpert')->with('messageSuccess', 'Thành Công');
    }

    public function adminExpertEvent(Request $request, $id)
    {
        Utility::setBackUrlCookie($request, '/admin/userExpert?');

        $expert = User::select('id', 'username')
            ->with(['expertInformation' => function($query) {
                $query->select('user_id');
            }])->find($id);

        if(empty($expert) || empty($expert->expertInformation))
            return view('backend.errors.404');

        $dataProvider = ExpertEvent::where('expert_id', $id)
            ->orderBy('id', 'desc');

        $inputs = $request->all();

        if(count($inputs) > 0)
        {
            if(!empty($inputs['name']))
                $dataProvider->where('name', 'like', '%' . $inputs['name'] . '%');
        }

        $dataProvider = $dataProvider->paginate(GridView::ROWS_PER_PAGE);

        $columns = [
            [
                'title' => 'Thời Gian',
                'data' => 'created_at',
            ],
            [
                'title' => 'Tên',
                'data' => 'name',
            ],
            [
                'title' => 'Url',
                'data' => 'url',
            ],
        ];

        $gridView = new GridView($dataProvider, $columns);
        $gridView->setFilters([
            [
                'title' => 'Tên',
                'name' => 'name',
                'type' => 'input',
            ],
        ]);
        $gridView->setFilterValues($inputs);

        return view('backend.experts.admin_expert_event', [
            'expert' => $expert,
            'gridView' => $gridView,
        ]);
    }
}