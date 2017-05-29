@extends('backend.layouts.main')

@section('page_heading', 'Chỉnh Sửa Mã Giảm Giá - ' . $discount->code)

@section('section')

    <form action="{{ action('Backend\DiscountController@editDiscount', ['id' => $discount->id]) }}" method="post">

        @include('backend.discounts.partials.discount_form')

    </form>

@stop