@extends('backend.layouts.main')

@section('page_heading', 'Chứng Chỉ Mới')

@section('section')

    <form action="{{ action('Backend\CertificateController@createCertificate') }}" method="post">

        @include('backend.certificates.partials.certificate_form')

    </form>

@stop