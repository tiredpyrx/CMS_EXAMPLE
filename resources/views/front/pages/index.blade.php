@extends('front.pages.template.index')

@section('page')
    <div class="p-5">
        @foreach (getCategoryPosts('Home Sections') as $section)
            @if ($section->active)
                @include('front.partials.sections.' . $section->field('view'))
                <hr>
            @endif
        @endforeach
    </div>
@endsection
