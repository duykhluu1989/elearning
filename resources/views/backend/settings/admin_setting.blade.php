@extends('backend.layouts.main')

@section('page_heading', 'Tổng Quan')

@section('section')

    <form action="{{ action('Backend\SettingController@adminSetting') }}" method="post">

        <div class="box box-primary">
            <div class="box-header with-border">
                <button type="submit" class="btn btn-primary">Cập Nhật</button>
            </div>
            <div class="box-body">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="form-group">
                            <label>{{ $settings[\App\Models\Setting::WEB_LOGO]->name }}</label>
                            <div class="input-group">
                                <input type="text" class="form-control" name="{{ \App\Models\Setting::WEB_LOGO }}" value="{{ old(\App\Models\Setting::WEB_LOGO, $settings[\App\Models\Setting::WEB_LOGO]->value) }}" />
                                <span class="input-group-btn">
                                    <button type="button" class="btn btn-default" id="WebLogoElFinderPopupOpen"><i class="fa fa-image fa-fw"></i></button>
                                </span>
                            </div>
                            @if(!empty(old(\App\Models\Setting::WEB_LOGO, $settings[\App\Models\Setting::WEB_LOGO]->value)))
                                <img src="{{ old(\App\Models\Setting::WEB_LOGO, $settings[\App\Models\Setting::WEB_LOGO]->value) }}" width="20%" alt="Web Logo" />
                            @endif
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <div class="form-group">
                            <label>{{ $settings[\App\Models\Setting::WEB_TITLE]->name }}</label>
                            <input type="text" class="form-control" name="{{ \App\Models\Setting::WEB_TITLE }}" value="{{ old(\App\Models\Setting::WEB_TITLE, $settings[\App\Models\Setting::WEB_TITLE]->value) }}" />
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <div class="form-group">
                            <label>{{ $settings[\App\Models\Setting::WEB_DESCRIPTION]->name }}</label>
                            <input type="text" class="form-control" name="{{ \App\Models\Setting::WEB_DESCRIPTION }}" value="{{ old(\App\Models\Setting::WEB_DESCRIPTION, $settings[\App\Models\Setting::WEB_DESCRIPTION]->value) }}" />
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <div class="form-group">
                            <label>{{ $settings[\App\Models\Setting::WEB_KEYWORD]->name }}</label>
                            <input type="text" class="form-control" name="{{ \App\Models\Setting::WEB_KEYWORD }}" value="{{ old(\App\Models\Setting::WEB_KEYWORD, $settings[\App\Models\Setting::WEB_KEYWORD]->value) }}" />
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <div class="form-group{{ $errors->has(\App\Models\Setting::CONTACT_EMAIL) ? ' has-error': '' }}">
                            <label>{{ $settings[\App\Models\Setting::CONTACT_EMAIL]->name }}</label>
                            <input type="text" class="form-control" name="{{ \App\Models\Setting::CONTACT_EMAIL }}" value="{{ old(\App\Models\Setting::CONTACT_EMAIL, $settings[\App\Models\Setting::CONTACT_EMAIL]->value) }}" />
                            @if($errors->has(\App\Models\Setting::CONTACT_EMAIL))
                                <span class="help-block">{{ $errors->first(\App\Models\Setting::CONTACT_EMAIL) }}</span>
                            @endif
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group{{ $errors->has(\App\Models\Setting::EXCHANGE_USD_RATE) ? ' has-error': '' }}">
                            <label>{{ $settings[\App\Models\Setting::EXCHANGE_USD_RATE]->name }} <i>(bắt buộc)</i></label>
                            <div class="input-group">
                                <input type="text" class="form-control InputForNumber" name="{{ \App\Models\Setting::EXCHANGE_USD_RATE }}" value="{{ old(\App\Models\Setting::EXCHANGE_USD_RATE, \App\Libraries\Helpers\Utility::formatNumber($settings[\App\Models\Setting::EXCHANGE_USD_RATE]->value)) }}" required="required" />
                                <span class="input-group-addon">VND = 1 USD</span>
                            </div>
                            @if($errors->has(\App\Models\Setting::EXCHANGE_USD_RATE))
                                <span class="help-block">{{ $errors->first(\App\Models\Setting::EXCHANGE_USD_RATE) }}</span>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-4">
                        <div class="form-group{{ $errors->has(\App\Models\Setting::EXCHANGE_POINT_RATE) ? ' has-error': '' }}">
                            <label>{{ $settings[\App\Models\Setting::EXCHANGE_POINT_RATE]->name }} <i>(bắt buộc)</i></label>
                            <div class="input-group">
                                <input type="text" class="form-control InputForNumber" name="{{ \App\Models\Setting::EXCHANGE_POINT_RATE }}" value="{{ old(\App\Models\Setting::EXCHANGE_POINT_RATE, \App\Libraries\Helpers\Utility::formatNumber($settings[\App\Models\Setting::EXCHANGE_POINT_RATE]->value)) }}" required="required" />
                                <span class="input-group-addon">VND = 1 Điểm</span>
                            </div>
                            @if($errors->has(\App\Models\Setting::EXCHANGE_POINT_RATE))
                                <span class="help-block">{{ $errors->first(\App\Models\Setting::EXCHANGE_POINT_RATE) }}</span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            <div class="box-footer">
                <button type="submit" class="btn btn-primary">Cập Nhật</button>
            </div>
        </div>
        {{ csrf_field() }}

    </form>

@stop

@push('stylesheets')
    <link rel="stylesheet" href="{{ asset('assets/css/colorbox.css') }}">
@endpush

@push('scripts')
    <script src="{{ asset('assets/js/jquery.colorbox-min.js') }}"></script>
    <script type="text/javascript">
        var elFinderSelectedFile;
        var imageTarget;

        $('#WebLogoElFinderPopupOpen').click(function() {
            imageTarget = 'logo';
            openElFinderPopup($(this));
        });

        function openElFinderPopup(elem)
        {
            elFinderSelectedFile = elem.parent().parent().find('input').first();

            $.colorbox({
                href: '{{ action('Backend\ElFinderController@popup') }}',
                iframe: true,
                width: '1200',
                height: '600',
                closeButton: false
            });
        }

        function elFinderProcessSelectedFile(fileUrl)
        {
            elFinderSelectedFile.val(fileUrl);

            if(elFinderSelectedFile.parent().parent().find('img').length > 0)
                elFinderSelectedFile.parent().parent().find('img').first().prop('src', fileUrl);
            else
            {
                if(imageTarget == 'logo')
                {
                    elFinderSelectedFile.parent().parent().append('' +
                        '<img src="' + fileUrl + '" width="20%" alt="Web Logo" />' +
                    '');
                }
            }
        }
    </script>
@endpush