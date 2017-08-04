@extends('backend.layouts.main')

@section('page_heading', 'Chuyên Gia ' . $event->user->username . ' - Chỉnh Sửa Sự Kiện - ' . $event->name)

@section('section')

    <form action="{{ action('Backend\ExpertController@editExpertEvent', ['id' => $event->id]) }}" method="post">

        @include('backend.experts.partials.expert_event_form')

    </form>

@stop