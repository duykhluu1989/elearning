@extends('backend.layouts.main')

@section('page_heading', 'Chính Sách Cộng Tác Viên')

@section('section')

    <form action="{{ action('Backend\SettingController@adminSettingCollaborator') }}" method="post">

        <div class="box box-primary">
            <div class="box-header with-border">
                <button type="submit" class="btn btn-primary">Cập Nhật</button>
            </div>
            <div class="box-body">
                <div class="row">
                </div>
            </div>
            <div class="box-footer">
                <button type="submit" class="btn btn-primary">Cập Nhật</button>
            </div>
        </div>
        {{ csrf_field() }}

    </form>

@stop