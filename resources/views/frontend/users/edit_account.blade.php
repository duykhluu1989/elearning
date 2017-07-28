@extends('frontend.layouts.main')

@section('page_heading', trans('theme.account'))

@section('section')

    @include('frontend.layouts.partials.header')

    @include('frontend.layouts.partials.breabcrumb', ['breabcrumbTitle' => trans('theme.account')])

    <main>
        <section class="khoahoc bg_gray">
            <div class="container">
                <div class="row">
                    <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
                        <div class="navleft">
                            <h5>@lang('theme.account')</h5>
                            <ul class="list_navLeft">
                                <li class="active"><a href="javascript:void(0)">@lang('theme.account_general')</a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                        <div class="main_content">
                            <form action="{{ action('Frontend\UserController@editAccount') }}" method="POST" enctype="multipart/form-data">
                                <div class="row avatar_hv">
                                    <div class="col-lg-3 boxmH display_table">
                                        <div class="table_content">

                                            @if($user->avatar)
                                                <img src="{{ $user->avatar }}" alt="User Avatar" class="img-responsive">
                                            @endif

                                        </div>
                                    </div>
                                    <div class="col-lg-6 boxmH display_table">
                                        <div class="table_content">
                                            <p>{{ $user->profile->name }}</p>
                                        </div>
                                    </div>
                                    <div class="col-lg-3 boxmH display_table">
                                        <div class="table_content">
                                            <div class="form-group">
                                                <input type="file" name="avatar" id="doiavatar" class="form-control filestyle" data-buttonText="@lang('theme.up_avatar')" data-input="false" data-iconName="false" data-classButton="btn_Doiavatar" accept="{{ implode(', ', \App\Libraries\Helpers\Utility::getValidImageExt(true)) }}" />
                                                @if($errors->has('avatar'))
                                                    <div class="form-group has-error">
                                                        <span class="help-block">* {{ $errors->first('avatar') }}</span>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-6">
                                        <h4>@lang('theme.account_general')</h4>
                                        <div class="frm_thongtincanhan boxmH">
                                            <div class="form-group">
                                                <label>* @lang('theme.first_name')</label>
                                                <input type="text" class="form-control" name="first_name" value="{{ old('first_name', $user->profile->first_name) }}" required="required" />
                                                @if($errors->has('first_name'))
                                                    <div class="form-group has-error">
                                                        <span class="help-block">* {{ $errors->first('first_name') }}</span>
                                                    </div>
                                                @endif
                                            </div>
                                            <div class="form-group">
                                                <label>@lang('theme.last_name')</label>
                                                <input type="text" class="form-control" name="last_name" value="{{ old('last_name', $user->profile->last_name) }}" />
                                                @if($errors->has('last_name'))
                                                    <div class="form-group has-error">
                                                        <span class="help-block">* {{ $errors->first('last_name') }}</span>
                                                    </div>
                                                @endif
                                            </div>
                                            <div class="form-group">
                                                <label>@lang('theme.gender')</label>
                                                <div class="radio">
                                                    <?php
                                                    $gender = old('gender', $user->profile->gender);
                                                    ?>
                                                    <div>
                                                        @foreach(\App\Models\Profile::getProfileGender() as $value => $label)
                                                            @if($gender == $value)
                                                                <label class="radio-inline">
                                                                    <input type="radio" name="gender" checked="checked" value="{{ $value }}">{{ $label }}
                                                                </label>
                                                            @else
                                                                <label class="radio-inline">
                                                                    <input type="radio" name="gender" value="{{ $value }}">{{ $label }}
                                                                </label>
                                                            @endif
                                                        @endforeach
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label>@lang('theme.phone')</label>
                                                <input type="text" class="form-control" name="phone" value="{{ old('phone', $user->profile->phone) }}" />
                                                @if($errors->has('phone'))
                                                    <div class="form-group has-error">
                                                        <span class="help-block">* {{ $errors->first('phone') }}</span>
                                                    </div>
                                                @endif
                                            </div>
                                            <div class="form-group">
                                                <label>@lang('theme.birthday')</label>
                                                <input type="date" name="birthday" class="form-control DatePicker" value="{{ old('birthday', $user->profile->birthday) }}" />
                                                @if($errors->has('birthday'))
                                                    <div class="form-group has-error">
                                                        <span class="help-block">* {{ $errors->first('birthday') }}</span>
                                                    </div>
                                                @endif
                                            </div>
                                            <div class="form-group">
                                                <label>@lang('theme.address')</label>
                                                <input type="text" class="form-control" name="address" value="{{ old('address', $user->profile->address) }}" />
                                                @if($errors->has('address'))
                                                    <div class="form-group has-error">
                                                        <span class="help-block">* {{ $errors->first('address') }}</span>
                                                    </div>
                                                @endif
                                            </div>
                                            <div class="form-group">
                                                <label>@lang('theme.province')</label>
                                                <select id="ProfileProvince" name="province" class="form-control">
                                                    <?php
                                                    $province = old('province', \App\Libraries\Helpers\Area::getCodeFromName($user->profile->province));
                                                    ?>

                                                    <option value=""></option>

                                                    @foreach(\App\Libraries\Helpers\Area::$provinces as $code => $data)
                                                        @if($province == $code)
                                                            <option selected="selected" value="{{ $code }}">{{ $data['name'] }}</option>
                                                        @else
                                                            <option value="{{ $code }}">{{ $data['name'] }}</option>
                                                        @endif
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="form-group">
                                                <label>@lang('theme.district')</label>
                                                <select id="ProfileDistrict" name="district" class="form-control">
                                                    <?php
                                                    $district = old('district', \App\Libraries\Helpers\Area::getCodeFromName($user->profile->district, 'district'));
                                                    ?>

                                                    <option value=""></option>

                                                    @if($district && isset(\App\Libraries\Helpers\Area::$provinces[$province]['cities']))
                                                        @foreach(\App\Libraries\Helpers\Area::$provinces[$province]['cities'] as $code => $data)
                                                            @if($district == $code)
                                                                <option selected="selected" value="{{ $code }}">{{ $data }}</option>
                                                            @else
                                                                <option value="{{ $code }}">{{ $data }}</option>
                                                            @endif
                                                        @endforeach
                                                    @endif
                                                </select>
                                            </div>
                                            <div class="form-group">
                                                <label>@lang('theme.title')</label>
                                                <input type="text" class="form-control" name="title" value="{{ old('title', $user->profile->title) }}" />
                                                @if($errors->has('title'))
                                                    <div class="form-group has-error">
                                                        <span class="help-block">* {{ $errors->first('title') }}</span>
                                                    </div>
                                                @endif
                                            </div>
                                            <button type="submit" class="btn btn-lg btn-block btnRed"><i class="fa fa-floppy-o" aria-hidden="true"></i>@lang('theme.save')</button>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <h4>Thông tin đăng nhập</h4>
                                        <div class="frm_thongtindangnhap boxmH">
                                            <div class="form-group">
                                                <label for="">Tên đăng nhập</label>
                                                <input type="text" class="form-control" id="">
                                            </div>
                                            <div class="form-group">
                                                <label for="">Mật khẩu hiện tại</label>
                                                <input type="password" class="form-control" id="">
                                            </div>
                                            <div class="form-group">
                                                <label for="">Mật khẩu mới</label>
                                                <input type="password" class="form-control" id="">
                                            </div>
                                            <div class="form-group">
                                                <label for="">Nhập lại mật khẩu mới</label>
                                                <input type="password" class="form-control" id="">
                                            </div>
                                            <button type="submit" class="btn btn-lg btn-block btnRed mb15"><i class="fa fa-floppy-o" aria-hidden="true"></i> LƯU</button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>

@stop