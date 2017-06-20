@extends('backend.layouts.main')

@section('page_heading', $caseStep->caseAdvice->name . ' - Bước Giải Quyết Mới')

@section('section')

    <form action="{{ action('Backend\CaseAdviceController@createCaseAdviceStep', ['id' => $caseStep->case_id]) }}" method="post">

        @include('backend.caseAdvices.partials.case_advice_step_form')

    </form>

@stop