@extends('front.pages.template.index')
@section('page')
    {{-- @dd($page->fields()->where("handler", "sibling1")->first()->fields->pluck('value')->toArray()) --}}
    @foreach ($page->field('sibling1') as $siblings) 
        @foreach ($siblings as $sibling)
            {{ $sibling }}
            @if (($loop->iteration) % 2 === 0)
                <br>
            @endif
        @endforeach
    @endforeach
@endsection
