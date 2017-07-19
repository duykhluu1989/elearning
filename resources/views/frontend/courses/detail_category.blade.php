@extends('frontend.layouts.main')

@section('og_description', \App\Libraries\Helpers\Utility::getValueByLocale($category, 'name'))

@section('page_heading', \App\Libraries\Helpers\Utility::getValueByLocale($category, 'name'))

@section('section')

    @include('frontend.layouts.partials.header')

@stop