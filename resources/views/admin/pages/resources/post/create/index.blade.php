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
            <div class="col-span-9">
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
            <div class="col-span-3">
                <x-document-panel>
                    <div class="grid gap-6">
                        <div class="relative max-w-sm flex gap-4">
                            <div class="pointer-events-none flex items-center">
                                <svg class="h-4 w-4 text-gray-500 dark:text-gray-400" aria-hidden="true"
                                    xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                                    <path
                                        d="M20 4a2 2 0 0 0-2-2h-2V1a1 1 0 0 0-2 0v1h-3V1a1 1 0 0 0-2 0v1H6V1a1 1 0 0 0-2 0v1H2a2 2 0 0 0-2 2v2h20V4ZM0 18a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V8H0v10Zm5-8h10a1 1 0 0 1 0 2H5a1 1 0 0 1 0-2Z" />
                                </svg>
                            </div>
                            <div>
                                <input name="publish_date" type="date"
                                class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-blue-500 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:placeholder-gray-400 dark:focus:border-blue-500 dark:focus:ring-blue-500"
                                placeholder="Select date">
                            </div>
                        </div>
                        <div class="flex items-center">
                            <input @checked(old('active') ? old('active') : true) name="active" id="active" type="checkbox"
                                class="h-4 w-4 rounded border-gray-300 bg-gray-100 text-blue-600 focus:ring-2 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:ring-offset-gray-800 dark:focus:ring-blue-600">
                            <label for="active"
                                class="ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">Aktif</label>
                        </div>
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
