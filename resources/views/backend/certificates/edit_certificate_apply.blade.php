@extends('backend.layouts.main')

@section('page_heading', 'Chỉnh Sửa Đăng Kí - ' . $apply->name)

@section('section')

    <form action="{{ action('Backend\CertificateController@editCertificateApply', ['id' => $apply->id]) }}" method="post">

        @include('backend.certificates.partials.certificate_apply_form')

    </form>

@stop