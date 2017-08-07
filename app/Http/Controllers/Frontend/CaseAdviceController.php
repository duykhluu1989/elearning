<?php

namespace App\Http\Controllers\Frontend;

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

    public function detailCaseAdvice($id, $slug)
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

        Utility::viewCount($advice, 'view_count', Utility::VIEW_ADVICE_COOKIE_NAME);

        return view('frontend.caseAdvices.detail_case_advice', [
            'advice' => $advice,
        ]);
    }
}