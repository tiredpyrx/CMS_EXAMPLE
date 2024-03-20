@extends('front.pages.template.index')
@section('page')
    {{ $page->title }}
    <hr>
    {{ $page->slug }}
    <hr>

    @foreach ($page->field('multi') as $opt)
        {{ $opt }} <br>
    @endforeach
    <hr>

    @foreach ($page->field('multi2') as $opt)
        {{ $opt }} <br>
    @endforeach
    <hr>

    @foreach ($page->field('sibling1') as $opt)
        {{ $opt }} <br>
    @endforeach
    <hr>

    <article style="padding: 0 30px" class="ck-content">
        {!! $page->field('content') !!}
    </article>
    <article>
        {{ $page->field('content2') }}
    </article>
@endsection
