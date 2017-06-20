@extends('backend.layouts.main')

@section('page_heading', $caseStep->caseAdvice->name . ' - Chỉnh Sửa Bước ' . $caseStep->step)

@section('section')

    <form action="{{ action('Backend\CaseAdviceController@editCaseAdviceStep', ['id' => $caseStep->id]) }}" method="post">

        @include('backend.caseAdvices.partials.case_advice_step_form')

    </form>

@stop