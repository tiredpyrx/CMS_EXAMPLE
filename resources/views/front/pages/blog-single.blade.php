@extends('front.pages.template.index')

@section('page')
    <div style="background: #f5f5f5" class="min-vh-100 py-5">
        <div class="w-50 mx-auto">
            {{-- <div class="py-4 text-center">
                <h1 class="mb-1">{{ $page->field('general-title') }}</h1>
                <div class="fw-bold text-muted">{{ $page->field('small-title') }}</div>
            </div> --}}
            <div>
                <img src="{{ $page->field('general-image', collect([]))->get('source') }}">
                <span>{{ $page->field('general-image')->smartGet('title', 'general image caption') }}</span>
            </div>
            <div class="my-4 border p-5">
                @foreach ($page->field('page-images') as $image)
                    <img class="img-fluid mb-2" src="{{ $image['source'] }}" alt="">
                @endforeach
            </div>
            {{-- <article>
                {!! $page->field('content') !!}
            </article> --}}
        </div>
    </div>
@endsection
