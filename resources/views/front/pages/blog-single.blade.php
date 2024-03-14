@extends('templates.front')

@section('content')
    @foreach ($page->field('myarray') as $item)
        {{ $item }} <br>
    @endforeach
    @foreach ($page->field('myarray2') as $item)
        {{ $item }} <br>
    @endforeach
    @foreach ($page->field('myfields') as $siblings)
        @foreach ($siblings as $sibling)
            {{ $sibling }}
            @unless ($loop->iteration % 2 === 0)
                -
            @else
                <br>
            @endif
        @endforeach
        @endforeach
    @endsection
