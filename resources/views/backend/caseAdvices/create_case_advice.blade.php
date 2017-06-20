@extends('backend.layouts.main')

@section('page_heading', 'Tình Huống Mới')

@section('section')

    <form action="{{ action('Backend\CaseAdviceController@createCaseAdvice') }}" method="post">

        @include('backend.caseAdvices.partials.case_advice_form')

    </form>

@stop