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
    <x-document-panel>
        <form class="app-form" action="{{ route('posts.store', $category->id) }}" method="POST">
            @csrf
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
            <div class="mt-6">
                <button type="submit" class="btn-primary">Ekle</button>
            </div>
        </form>
    </x-document-panel>
@endsection

@push('js')
    @livewireScripts
@endpush
