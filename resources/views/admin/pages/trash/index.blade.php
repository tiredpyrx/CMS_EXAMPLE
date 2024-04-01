@extends('templates.admin')

@section('content')
    <x-document-header title="Çöp Kutusu" />
    <div class="grid grid-cols-2 gap-6">
        @foreach ($resources as $resource)
            <a href="#">
                <x-document-panel class="relative hover:scale-105 duration-300 w-full h-full !mb-0">
                    <div class="absolute right-2 top-2">
                        <div class="text-sm font-semibold">
                            {{ $resource['all_count'] }}
                        </div>
                    </div>
                    <div class="text-xl font-bold">
                        {{ ucfirst($resource['title']) }}
                    </div>
                </x-document-panel>
            </a>
        @endforeach
    </div>
@endsection
