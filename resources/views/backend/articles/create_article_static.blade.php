@extends('backend.layouts.main')

@section('page_heading', 'Trang Tĩnh Mới')

@section('section')

    <form action="{{ action('Backend\ArticleController@createArticleStatic') }}" method="post">

        @include('backend.articles.partials.article_static_form')

    </form>

@stop