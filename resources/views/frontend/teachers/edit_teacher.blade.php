@extends('frontend.layouts.main')

@section('page_heading', trans('theme.teacher_label'))

@section('section')

    @include('frontend.layouts.partials.header')

    @include('frontend.layouts.partials.headtext')

    @include('frontend.layouts.partials.breabcrumb', ['breabcrumbTitle' => trans('theme.teacher_label')])

    <main>
        <section class="khoahoc bg_gray">
            <div class="container">
                <div class="row">
                    <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
                        <div class="navleft">
                            <h5>@lang('theme.teacher_label')</h5>

                            @include('frontend.teachers.partials.navigation')

                        </div>
                    </div>
                    <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                        <div class="main_content">
                            <div class="row">
                                <div class="col-lg-6">
                                    <h4>@lang('theme.account_general')</h4>
                                    <div class="frm_thongtincanhan boxmH">
                                        <div class="form-group">
                                            <label>@lang('theme.current_commission')</label>
                                            <input type="text" class="form-control" value="{{ \App\Libraries\Helpers\Utility::formatNumber($user->teacherInformation->current_commission) . 'đ' }}" readonly="readonly" />
                                        </div>
                                        <div class="form-group">
                                            <label>@lang('theme.total_commission')</label>
                                            <input type="text" class="form-control" value="{{ \App\Libraries\Helpers\Utility::formatNumber($user->teacherInformation->total_commission) . 'đ' }}" readonly="readonly" />
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>

@stop