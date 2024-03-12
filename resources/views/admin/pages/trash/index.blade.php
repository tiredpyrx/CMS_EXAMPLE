@extends('templates.admin')

@section('content')
    <x-document-header title="Çöp Kutusu" />
    <div class="grid grid-cols-2 gap-6">
        @foreach ($resources as $resource)
            <a href="#">
                <x-document-panel style="background-image: url({{ $resource['image'] }})"
                    class="relative h-72 bg-cover bg-center bg-no-repeat overflow-hidden mb-0">
                    <div class="group absolute inset-0 h-full w-full backdrop-blur-sm">
                        <div
                            class="absolute -bottom-10 left-1/2 -translate-x-1/2 translate-y-1/2 duration-500 group-hover:bottom-1/2">
                            <div class="text-4xl font-bold text-gray-50">
                                {{ ucfirst($resource['primary_text']) }}
                            </div>
                        </div>
                    </div>
                </x-document-panel>
            </a>
        @endforeach
    </div>
@endsection
