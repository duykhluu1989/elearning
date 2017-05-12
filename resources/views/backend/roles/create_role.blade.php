@extends('backend.layouts.main')

@section('page_heading', 'Create Role')

@section('section')

    <form action="{{ action('Backend\RoleController@createRole') }}" method="post">

        @include('backend.roles.partials.role_form')

    </form>

@stop