@extends('backend.layouts.main')

@section('page_heading', 'Chỉnh Sửa Tin Tức - ' . $article->name)

@section('section')

    <form action="{{ action('Backend\NewsController@editArticle', ['id' => $article->id]) }}" method="post">

        @include('backend.news.partials.article_form')

    </form>

@stop