@extends('front.pages.template.index')

@section('page')
    <div style="width:200px">
        <img class="img-fluid" src="{{ $page->field('section-image') }}" alt="">
    </div>
@endsection
