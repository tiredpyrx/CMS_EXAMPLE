@extends('front.pages.template.index')

@section('page')
    {{ $page->title }}
    <article>
        {{ $page->field('content') }}
    </article>
    <article>
        {{ $page->field('content2') }}
    </article>
@endsection
