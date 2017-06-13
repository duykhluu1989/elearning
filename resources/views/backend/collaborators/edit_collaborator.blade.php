@extends('backend.layouts.main')

@section('page_heading', 'Chỉnh Sửa Cộng Tác Viên - ' . $collaborator->collaboratorInformation->code)

@section('section')

    <form action="{{ action('Backend\CollaboratorController@editCollaborator', ['id' => $collaborator->id]) }}" method="post">

        <div class="box box-primary">
            <div class="box-header with-border">
                <button type="submit" class="btn btn-primary">Cập Nhật</button>
                <a href="{{ \App\Libraries\Helpers\Utility::getBackUrlCookie(action('Backend\UserController@adminUserCollaborator')) }}" class="btn btn-default">Quay Lại</a>
            </div>
            <div class="box-body">

            </div>
            <div class="box-footer">
                <button type="submit" class="btn btn-primary">Cập Nhật</button>
                <a href="{{ \App\Libraries\Helpers\Utility::getBackUrlCookie(action('Backend\UserController@adminUserCollaborator')) }}" class="btn btn-default">Quay Lại</a>
            </div>
        </div>
        {{ csrf_field() }}

    </form>

@stop