@extends('frontend.layouts.main')

@section('page_heading', trans('theme.collaborator'))

@section('section')

    @include('frontend.layouts.partials.header')

    @include('frontend.layouts.partials.headtext')

    @include('frontend.layouts.partials.breabcrumb', ['breabcrumbTitle' => trans('theme.collaborator')])

    <main>
        <section class="khoahoc bg_gray">
            <div class="container">
                <div class="row">
                    <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
                        <div class="navleft">
                            <h5>@lang('theme.collaborator')</h5>

                            @include('frontend.collaborators.partials.navigation')

                        </div>
                    </div>
                    <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                        <div class="main_content">
                            <div class="row">
                                <div class="col-lg-6">
                                    <h4>@lang('theme.account_general')</h4>
                                    <div class="frm_thongtincanhan boxmH">
                                        <div class="form-group">
                                            <label>@lang('theme.code')</label>
                                            <input type="text" class="form-control" value="{{ $user->collaboratorInformation->code }}" readonly="readonly" />
                                        </div>

                                        @if(!empty($collaborator->collaboratorInformation->parentCollaborator))
                                            <div class="form-group">
                                                <label>@lang('theme.manager')</label>
                                                <input type="text" class="form-control" value="{{ $user->collaboratorInformation->parentCollaborator->code }}" readonly="readonly" />
                                            </div>
                                        @endif

                                        <div class="form-group">
                                            <label>@lang('theme.rank')</label>
                                            <input type="text" class="form-control" value="{{ $user->collaboratorInformation->rank->name }}" readonly="readonly" />
                                        </div>
                                        <div class="form-group">
                                            <label>@lang('theme.create_discount')</label>
                                            <input type="text" class="form-control" value="{{ $user->collaboratorInformation->create_discount_percent . '%' }}" readonly="readonly" />
                                        </div>
                                        <div class="form-group">
                                            <label>@lang('theme.commission')</label>
                                            <input type="text" class="form-control" value="{{ $user->collaboratorInformation->commission_percent . '%' }}" readonly="readonly" />
                                        </div>
                                        <div class="form-group">
                                            <label>@lang('theme.current_revenue')</label>
                                            <input type="text" class="form-control" value="{{ \App\Libraries\Helpers\Utility::formatNumber($user->collaboratorInformation->current_revenue) . 'đ' }}" readonly="readonly" />
                                        </div>
                                        <div class="form-group">
                                            <label>@lang('theme.total_revenue')</label>
                                            <input type="text" class="form-control" value="{{ \App\Libraries\Helpers\Utility::formatNumber($user->collaboratorInformation->total_revenue) . 'đ' }}" readonly="readonly" />
                                        </div>
                                        <div class="form-group">
                                            <label>@lang('theme.current_commission')</label>
                                            <input type="text" class="form-control" value="{{ \App\Libraries\Helpers\Utility::formatNumber($user->collaboratorInformation->current_commission) . 'đ' }}" readonly="readonly" />
                                        </div>
                                        <div class="form-group">
                                            <label>@lang('theme.total_commission')</label>
                                            <input type="text" class="form-control" value="{{ \App\Libraries\Helpers\Utility::formatNumber($user->collaboratorInformation->total_commission) . 'đ' }}" readonly="readonly" />
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <h4>@lang('theme.bank_information')</h4>
                                    <div class="frm_thongtindangnhap boxmH">
                                        <form action="{{ action('Frontend\CollaboratorController@editCollaborator') }}" method="POST">
                                            <div class="form-group">
                                                <label>@lang('theme.bank')</label>
                                                <input type="text" class="form-control" name="bank" value="{{ old('bank', $user->collaboratorInformation->bank) }}" />
                                                @if($errors->has('bank'))
                                                    <div class="form-group has-error">
                                                        <span class="help-block">* {{ $errors->first('bank') }}</span>
                                                    </div>
                                                @endif
                                            </div>
                                            <div class="form-group">
                                                <label>@lang('theme.bank_name')</label>
                                                <input type="text" class="form-control" name="bank_holder" value="{{ old('bank_holder', $user->collaboratorInformation->bank_holder) }}" />
                                                @if($errors->has('bank_holder'))
                                                    <div class="form-group has-error">
                                                        <span class="help-block">* {{ $errors->first('bank_holder') }}</span>
                                                    </div>
                                                @endif
                                            </div>
                                            <div class="form-group">
                                                <label>@lang('theme.bank_number')</label>
                                                <input type="text" class="form-control" name="bank_number" value="{{ old('bank_number', $user->collaboratorInformation->bank_number) }}" />
                                                @if($errors->has('bank_number'))
                                                    <div class="form-group has-error">
                                                        <span class="help-block">* {{ $errors->first('bank_number') }}</span>
                                                    </div>
                                                @endif
                                            </div>
                                            <button type="submit" class="btn btn-lg btn-block btnRed"><i class="fa fa-floppy-o" aria-hidden="true"></i>@lang('theme.save')</button>
                                            {{ csrf_field() }}
                                        </form>
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