@extends('backend.layouts.main')

@section('page_heading', 'Chỉnh Sửa Chứng Chỉ - ' . $certificate->name)

@section('section')

    <form action="{{ action('Backend\CertificateController@editCertificate', ['id' => $certificate->id]) }}" method="post">

        @include('backend.certificates.partials.certificate_form')

    </form>

@stop