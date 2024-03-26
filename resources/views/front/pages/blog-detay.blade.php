@extends('front.pages.template.index')

@section('page')
    {{ $page->field('title') }}
    <h1 class="mt-4">
        {{ $page->field('genel-alan-basligi') }}
    </h1>
    {!! $page->field('sayfa-icerik') !!}
    <ul>
        @foreach ($page->field('oneriler', []) as $option)
            <li>{{ $option }} </li>
        @endforeach
    </ul>
@endsection
