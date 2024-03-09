@extends('templates.admin')

@section('content')
    <x-document-header title="{{ $category->title }} Kategorisi İçin Gönderi Ekle">
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
                    <div class="col-span-{{ $field->column }}">
                        <label default for="{{ $field->handler }}">{{ $field->label }}</label>
                        <input
                            default
                            name="{{ $field->handler }}"
                            id="{{ $field->handler }}"
                            value="{{ $field->value }}"
                            type="{{ $field->type }}"
                        />
                    </div>
                @endforeach
            </div>
            <div class="mt-6">
                <button type="submit" class="btn-primary">Ekle</button>
            </div>
        </form>
    </x-document-panel>
@endsection
