@extends('backend.layouts.main')

@section('page_heading', 'Chuyên Gia ' . $event->user->username . ' - Sự Kiện Mới')

@section('section')

    <form action="{{ action('Backend\ExpertController@createExpertEvent', ['id' => $event->expert_id]) }}" method="post">

        @include('backend.experts.partials.expert_event_form')

    </form>

@stop