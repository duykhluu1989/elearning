@extends('backend.layouts.main')

@section('page_heading', 'Tài Khoản')

@section('section')

    <form action="{{ action('Backend\UserController@editUser', ['id' => $user->id]) }}" method="post" enctype="multipart/form-data">

        {{ csrf_field() }}

    </form>

@stop