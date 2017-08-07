@extends('frontend.layouts.main')

@section('og_description', \App\Libraries\Helpers\Utility::getValueByLocale($page, 'short_description'))

@section('og_image', $page->image)

@section('page_heading', \App\Libraries\Helpers\Utility::getValueByLocale($page, 'name'))

@section('section')

    @include('frontend.layouts.partials.header')

    @include('frontend.layouts.partials.headtext')

    @include('frontend.layouts.partials.breabcrumb', ['breabcrumbTitle' => \App\Libraries\Helpers\Utility::getValueByLocale($page, 'name')])

    <main>
        <section class="khoahoc bg_gray">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="main_content mb60">
                            <h2>{{ \App\Libraries\Helpers\Utility::getValueByLocale($page, 'name') }}</h2>

                            @if(!empty($page->image))
                                <img src="{{ $page->image }}" alt="{{ \App\Libraries\Helpers\Utility::getValueByLocale($page, 'name') }}" class="img-responsive w100p mb15">
                            @endif

                            <?php
                            echo \App\Libraries\Helpers\Utility::getValueByLocale($page, 'content');
                            ?>

                            @if(auth()->guest())
                                <h2>@lang('theme.teacher_register')</h2>
                                <ul class="nav nav-tabs tabs_info">
                                    <li class="active"><a data-toggle="tab" href="#section_giangvien">@lang('theme.account')</a></li>
                                    <li><a data-toggle="tab" href="#section_tochucGD">@lang('theme.educational_organization')</a></li>
                                </ul>
                                <div class="tabs_info_content mb60">
                                    <div class="row">
                                        <div class="col-lg-8 col-lg-offset-2 tab-content">
                                            <div id="section_giangvien" class="tab-pane fade in active">
                                                <form action="{{ action('Frontend\UserController@registerTeacher') }}" method="POST" role="form" class="frm_thongtinctv TeacherSignUpForm">
                                                    <div class="form-group">
                                                        <label>* @lang('theme.first_name')</label>
                                                        <input type="text" class="form-control" name="first_name" required="required" />
                                                    </div>
                                                    <div class="form-group">
                                                        <label>@lang('theme.last_name')</label>
                                                        <input type="text" class="form-control" name="last_name" />
                                                    </div>
                                                    <div class="form-group">
                                                        <label>* Email</label>
                                                        <input type="text" class="form-control" name="email" required="required" />
                                                    </div>
                                                    <div class="form-group">
                                                        <label>* @lang('theme.password')</label>
                                                        <input type="password" class="form-control" name="password" required="required" />
                                                    </div>
                                                    <div class="form-group">
                                                        <label>@lang('theme.birthday')</label>
                                                        <input type="text" name="birthday" class="form-control DatePicker" />
                                                    </div>
                                                    <div class="form-group">
                                                        <label>@lang('theme.gender')</label>
                                                        <div class="radio">
                                                            <div>
                                                                @foreach(\App\Models\Profile::getProfileGender() as $value => $label)
                                                                    <label class="radio-inline">
                                                                        <input type="radio" name="gender" value="{{ $value }}">{{ $label }}
                                                                    </label>
                                                                @endforeach
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <label>@lang('theme.phone')</label>
                                                        <input type="text" class="form-control" name="phone" />
                                                    </div>
                                                    <div class="form-group">
                                                        <label>@lang('theme.address')</label>
                                                        <input type="text" class="form-control" name="address" />
                                                    </div>
                                                    <div class="form-group">
                                                        <label>@lang('theme.province')</label>
                                                        <select id="ProfileProvince" name="province" class="form-control">
                                                            <option value=""></option>
                                                            @foreach(\App\Libraries\Helpers\Area::$provinces as $code => $data)
                                                                <option value="{{ $code }}">{{ $data['name'] }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    <div class="form-group">
                                                        <label>@lang('theme.district')</label>
                                                        <select id="ProfileDistrict" name="district" class="form-control">
                                                            <option value=""></option>
                                                        </select>
                                                    </div>
                                                    <div class="form-group">
                                                        <label>@lang('theme.title')</label>
                                                        <input type="text" class="form-control" name="title" />
                                                    </div>
                                                    {{ csrf_field() }}
                                                    <button type="submit" class="btn btn-lg btnRed">@lang('theme.save')</button>
                                                </form>
                                            </div>
                                            <div id="section_tochucGD" class="tab-pane fade">
                                                <form action="{{ action('Frontend\UserController@registerTeacher') }}" method="POST" role="form" class="frm_thongtinctv TeacherSignUpForm">
                                                    <div class="form-group">
                                                        <label>* @lang('theme.first_name')</label>
                                                        <input type="text" class="form-control" name="first_name" required="required" />
                                                    </div>
                                                    <div class="form-group">
                                                        <label>* Email</label>
                                                        <input type="text" class="form-control" name="email" required="required" />
                                                    </div>
                                                    <div class="form-group">
                                                        <label>* @lang('theme.password')</label>
                                                        <input type="password" class="form-control" name="password" required="required" />
                                                    </div>
                                                    <div class="form-group">
                                                        <label>@lang('theme.phone')</label>
                                                        <input type="text" class="form-control" name="phone" />
                                                    </div>
                                                    {{ csrf_field() }}
                                                    <input type="hidden" name="organization" value="1" />
                                                    <button type="submit" class="btn btn-lg btnRed">@lang('theme.save')</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif

                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>

@stop

@if(auth()->guest())
    @push('scripts')
        <script type="text/javascript">
            $('#ProfileProvince').change(function() {
                var districtElem = $('#ProfileDistrict');

                districtElem.html('' +
                    '<option value=""></option>' +
                '');

                if($(this).val() != '')
                {
                    $.ajax({
                        url: '{{ action('Frontend\OrderController@getListDistrict') }}',
                        type: 'get',
                        data: 'province_code=' + $(this).val(),
                        success: function(result) {
                            if(result)
                            {
                                result = JSON.parse(result);

                                for(var code in result)
                                {
                                    if(result.hasOwnProperty(code))
                                    {
                                        districtElem.append('' +
                                            '<option value="' + code + '">' + result[code] + '</option>' +
                                        '');
                                    }
                                }
                            }
                        }
                    });
                }
            });

            $('.TeacherSignUpForm').submit(function(e) {
                e.preventDefault();

                var formElem = $(this);

                formElem.find('input').each(function() {
                    $(this).parent().removeClass('has-error').find('span[class="help-block"]').first().remove();
                });

                $.ajax({
                    url: '{{ action('Frontend\UserController@registerTeacher') }}',
                    type: 'post',
                    data: '_token=' + $('input[name="_token"]').first().val() + '&' + formElem.serialize(),
                    success: function(result) {
                        if(result)
                        {
                            if(result == 'Success')
                            {
                                swal({
                                    title: '@lang('theme.sign_up_success')',
                                    type: 'success',
                                    allowEscapeKey: false,
                                    showConfirmButton: false
                                });

                                setTimeout(function() {
                                    location.reload();
                                }, 3000);
                            }
                            else
                            {
                                result = JSON.parse(result);

                                for(var name in result)
                                {
                                    if(result.hasOwnProperty(name))
                                    {
                                        formElem.find('input[name="' + name + '"]').first().parent().addClass('has-error').append('' +
                                            '<span class="help-block">' + result[name][0] + '</span>' +
                                        '');
                                    }
                                }
                            }
                        }
                    }
                });
            });
        </script>
    @endpush
@endif