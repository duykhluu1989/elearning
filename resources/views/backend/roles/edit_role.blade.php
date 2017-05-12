@extends('backend.layouts.main')

@section('page_heading', 'Edit Role - ' . $role->name)

@section('section')

    <form action="{{ action('Backend\RoleController@editRole', ['id' => $role->id]) }}" method="post">

        @include('backend.roles.partials.role_form')

    </form>

@stop