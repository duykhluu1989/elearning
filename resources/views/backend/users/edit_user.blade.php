@extends('backend.layouts.main')

@section('page_heading', 'Chỉnh Sửa Thành Viên - ' . $user->username)

@section('section')

    <form action="{{ action('Backend\UserController@editUser', ['id' => $user->id]) }}" method="post">

        @include('backend.users.partials.user_form')

    </form>

@stop