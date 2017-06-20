@extends('backend.layouts.main')

@section('page_heading', 'Chỉnh Sửa Tình Huống - ' . $case->name)

@section('section')

    <form action="{{ action('Backend\CaseAdviceController@editCaseAdvice', ['id' => $case->id]) }}" method="post">

        @include('backend.caseAdvices.partials.case_advice_form')

    </form>

@stop