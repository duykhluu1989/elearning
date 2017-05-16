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
                            <label>Tiêu Đề Web</label>
                            <input type="text" class="form-control" name="web_title" value="{{ old('web_title', $settings[\App\Models\Setting::WEB_TITLE]['value']) }}" />
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <div class="form-group">
                            <label>Mô Tả Web</label>
                            <input type="text" class="form-control" name="web_description" value="{{ old('web_description', $settings[\App\Models\Setting::WEB_DESCRIPTION]['value']) }}" />
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