@extends('front.pages.template.index')

@section('page')
    <div class="p-5">
        @foreach (getCategoryPosts('Home Sections') as $section)
            @include('front.pages.partials.sections.' . $section->field('section-view'))
            <hr>
        @endforeach
    </div>
@endsection
