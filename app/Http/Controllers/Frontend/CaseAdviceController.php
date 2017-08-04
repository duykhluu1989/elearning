<?php

namespace App\Http\Controllers\Frontend;

use Illuminate\Support\Facades\Cookie;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Libraries\Helpers\Utility;
use App\Models\CaseAdvice;

class CaseAdviceController extends Controller
{
    public function adminCaseAdvice($type = null)
    {
        $builder = CaseAdvice::select('id', 'name', 'name_en', 'slug', 'slug_en', 'description', 'description_en')
            ->where('status', Utility::ACTIVE_DB)
            ->orderBy('order', 'desc');

        if($type == str_slug(CaseAdvice::TYPE_ECONOMY_LABEL))
            $builder->where('type', CaseAdvice::TYPE_ECONOMY_DB);
        else if($type == str_slug(CaseAdvice::TYPE_LAW_LABEL))
            $builder->where('type', CaseAdvice::TYPE_LAW_DB);

        $advices = $builder->paginate(Utility::FRONTEND_ROWS_PER_PAGE);

        return view('frontend.caseAdvices.admin_case_advice', [
            'advices' => $advices,
            'type' => $type,
        ]);
    }

    public function detailCaseAdvice(Request $request, $id, $slug)
    {
        $advice = CaseAdvice::with(['caseAdviceSteps' => function($query) {
            $query->orderBy('step');
        }])->select('id', 'name', 'name_en', 'description', 'description_en', 'phone', 'adviser', 'view_count')
            ->where('id', $id)
            ->where('status', Utility::ACTIVE_DB)
            ->where(function($query) use($slug) {
                $query->where('slug', $slug)->orWhere('slug_en', $slug);
            })->first();

        if(empty($advice))
            return view('frontend.errors.404');

        if($request->hasCookie(Utility::VIEW_ADVICE_COOKIE_NAME))
        {
            $viewIds = $request->cookie(Utility::VIEW_ADVICE_COOKIE_NAME);
            $viewIds = explode(';', $viewIds);

            if(!in_array($advice->id, $viewIds))
            {
                $advice->increment('view_count', 1);

                $viewIds[] = $advice->id;
                $viewIds = implode(';', $viewIds);

                Cookie::queue(Utility::VIEW_ADVICE_COOKIE_NAME, $viewIds, Utility::MINUTE_ONE_DAY);
            }
        }
        else
        {
            $advice->increment('view_count', 1);

            Cookie::queue(Utility::VIEW_ADVICE_COOKIE_NAME, $advice->id, Utility::MINUTE_ONE_DAY);
        }

        return view('frontend.caseAdvices.detail_case_advice', [
            'advice' => $advice,
        ]);
    }
}