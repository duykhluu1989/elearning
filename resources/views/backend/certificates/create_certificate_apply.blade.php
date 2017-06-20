@extends('backend.layouts.main')

@section('page_heading', 'Đăng Kí Mới')

@section('section')

    <form action="{{ action('Backend\CertificateController@createCertificateApply') }}" method="post">

        @include('backend.certificates.partials.certificate_apply_form')

    </form>

@stop