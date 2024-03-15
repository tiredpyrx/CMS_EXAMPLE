@extends('templates.admin')

@push('css')
    @livewireStyles
@endpush

@section('content')
    <x-document-header title='"{{ shortenText($category->title, 20) }}" Kategorisi İçin Gönderi Ekle'>
        @if (auth()->user()->role == 'admin' || auth()->user()->role == 'editor')
            {{-- add button here --}}
        @endif
        <a href="{{ route('categories.show', $category->id) }}" class="btn-secondary">
            Kategoriyi Gör
        </a>
        <a href="{{ route('posts.index') }}" class="btn-secondary">
            Tüm Gönderiler
        </a>
    </x-document-header>
    <form class="app-form" action="{{ route('posts.store', $category->id) }}" method="POST">
        @csrf
        <div class="grid grid-cols-12 gap-x-4">
            <div class="col-span-10">
                <x-document-panel>
                    <div class="grid grid-cols-12 gap-4">
                        @foreach ($fields as $field)
                            <div class="{{ 'col-span-' . $field->column }}">
                                <div>
                                    <label default data-description="{{ $field->description }}" for="{{ $field->handler }}">
                                        {{ $field->required ? $field->label . '*' : $field->label }}
                                    </label>
                                    @includeWhen($field->active, 'admin.partials.fields.' . $field->type)
                                </div>
                            </div>
                        @endforeach
                    </div>
                </x-document-panel>
            </div>
            <div class="col-span-2">
                <x-document-panel>
                    <div class="relative max-w-sm">
                        <div class="absolute inset-y-0 start-0 flex items-center ps-3.5 pointer-events-none">
                          <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M20 4a2 2 0 0 0-2-2h-2V1a1 1 0 0 0-2 0v1h-3V1a1 1 0 0 0-2 0v1H6V1a1 1 0 0 0-2 0v1H2a2 2 0 0 0-2 2v2h20V4ZM0 18a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V8H0v10Zm5-8h10a1 1 0 0 1 0 2H5a1 1 0 0 1 0-2Z"/>
                          </svg>
                        </div>
                        <input datepicker type="text" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full ps-10 p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Select date">
                      </div>
                </x-document-panel>
            </div>
        </div>
        <div class="mt-6">
            <button type="submit" class="btn-primary">Ekle</button>
        </div>
    </form>
@endsection

@push('js')
    @livewireScripts
@endpush
