@extends('backend.layouts.main')

@section('page_heading', 'Chỉnh Sửa Bài Viết - ' . $article->name)

@section('section')

    <form action="{{ action('Backend\ArticleController@editArticle', ['id' => $article->id]) }}" method="post">

        @include('backend.articles.partials.article_form')

    </form>

@stop