@extends('backend.layouts.main')

@section('page_heading', 'Bài Viết Mới')

@section('section')

    <form action="{{ action('Backend\ArticleController@createArticle') }}" method="post">

        @include('backend.articles.partials.article_form')

    </form>

@stop