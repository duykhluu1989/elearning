@extends('backend.layouts.main')

@section('page_heading', 'Chỉnh Sửa Trang - ' . $article->name)

@section('section')

    <form action="{{ action('Backend\ArticleController@editArticleStatic', ['id' => $article->id]) }}" method="post">

        @include('backend.articles.partials.article_static_form')

    </form>

@stop