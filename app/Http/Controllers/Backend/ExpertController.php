<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Libraries\Widgets\GridView;
use App\Libraries\Helpers\Utility;
use App\Libraries\Helpers\Html;
use App\Models\User;
use App\Models\ExpertEvent;

class ExpertController extends Controller
{
    public function controlChangeOnlineExpert(Request $request, $online)
    {
        $ids = $request->input('ids');

        $experts = User::select('id')->with('expertInformation')->whereIn('id', explode(';', $ids))->get();

        foreach($experts as $expert)
        {
            if(!empty($expert->expertInformation))
            {
                if($online == Utility::INACTIVE_DB)
                    $expert->expertInformation->online = Utility::INACTIVE_DB;
                else
                    $expert->expertInformation->online = Utility::ACTIVE_DB;

                $expert->expertInformation->save();
            }
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
                'title' => 'Tên',
                'data' => function($row) {
                    echo Html::a($row->name, [
                        'href' => action('Backend\ExpertController@editExpertEvent', ['id' => $row->id]),
                    ]);
                },
            ],
            [
                'title' => 'Tên EN',
                'data' => 'name_en',
            ],
            [
                'title' => 'Url',
                'data' => 'url',
            ],
            [
                'title' => 'Thời Gian',
                'data' => 'created_at',
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

    public function createExpertEvent(Request $request, $id)
    {
        Utility::setBackUrlCookie($request, '/admin/userExpert/' . $id . '/event?');

        $expert = User::with(['expertInformation' => function($query) {
            $query->select('id', 'user_id');
        }])->select('id', 'username')
            ->find($id);

        if(empty($expert) || empty($expert->expertInformation))
            return view('backend.errors.404');

        $event = new ExpertEvent();
        $event->expert_id = $id;

        $event->user()->associate($expert);

        return $this->saveExpertEvent($request, $event);
    }

    public function editExpertEvent(Request $request, $id)
    {
        $event = ExpertEvent::with(['user' => function($query) {
            $query->select('id', 'username');
        }])->find($id);

        if(empty($event))
            return view('backend.errors.404');

        return $this->saveExpertEvent($request, $event, false);
    }

    protected function saveExpertEvent($request, $event, $create = true)
    {
        if($request->isMethod('post'))
        {
            $inputs = $request->all();

            $validator = Validator::make($inputs, [
                'name' => 'required',
                'url' => 'required',
            ]);

            if($validator->passes())
            {
                $event->name = $inputs['name'];
                $event->name_en = $inputs['name_en'];
                $event->url = $inputs['url'];
                $event->created_at = date('Y-m-d H:i:s');
                $event->save();

                return redirect()->action('Backend\ExpertController@editExpertEvent', ['id' => $event->id])->with('messageSuccess', 'Thành Công');
            }
            else
            {
                if($create == true)
                    return redirect()->action('Backend\ExpertController@createExpertEvent', ['id' => $event->expert_id])->withErrors($validator)->withInput();
                else
                    return redirect()->action('Backend\ExpertController@editExpertEvent', ['id' => $event->id])->withErrors($validator)->withInput();
            }
        }

        if($create == true)
        {
            return view('backend.experts.create_expert_event', [
                'event' => $event,
            ]);
        }
        else
        {
            return view('backend.experts.edit_expert_event', [
                'event' => $event,
            ]);
        }
    }

    public function deleteExpertEvent($id)
    {
        $event = ExpertEvent::find($id);

        if(empty($event))
            return view('backend.errors.404');

        $event->delete();

        return redirect(Utility::getBackUrlCookie(action('Backend\ExpertController@adminExpertEvent')))->with('messageSuccess', 'Thành Công');
    }
}