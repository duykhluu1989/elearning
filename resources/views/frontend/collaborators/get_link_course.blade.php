@extends('frontend.layouts.main')

@section('page_heading', (trans('theme.get_course_link') . ' ' . \App\Libraries\Helpers\Utility::getValueByLocale($course, 'name')))

@section('section')

    @include('frontend.layouts.partials.header')

    @include('frontend.layouts.partials.headtext')

    @include('frontend.layouts.partials.breabcrumb', ['breabcrumbTitle' => (trans('theme.get_course_link') . ' ' . \App\Libraries\Helpers\Utility::getValueByLocale($course, 'name'))])

    <main>
        <section class="bg_gray">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="main_content mb60">
                            <div class="row">
                                <div class="col-lg-12">
                                    <h3 class="text-center">@lang('theme.link_1')</h3>
                                    <textarea readonly="readonly" class="form-control">{{ $course->generateCollaboratorLink($user) }}</textarea>
                                </div>
                                <hr />
                                <div class="col-lg-12">
                                    <h3 class="text-center">Link coupon</h3>
                                    @if(!empty($discount))
                                        <textarea readonly="readonly" class="form-control">{{ $course->generateCollaboratorLink($user, $discount) }}</textarea>
                                    @else
                                        <textarea readonly="readonly" class="form-control"></textarea>
                                    @endif
                                </div>
                                <hr />
                                <div class="col-lg-12">
                                    <h3 class="text-center">@lang('theme.link_3')</h3>
                                    <textarea readonly="readonly" class="form-control">{{ $course->generateCollaboratorLink($user, $discount, true) }}</textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>

@stop