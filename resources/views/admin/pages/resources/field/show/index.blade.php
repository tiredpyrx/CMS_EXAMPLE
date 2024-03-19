@extends('templates.admin')

@section('content')
    <x-document-header title="{{ $field->label . ' Alan' }}" >
        <a href="{{ route('categories.show', $field->category_id) }}" class="btn-secondary">
            Kategoriyi Gör
        </a>
        <a href="{{ route('categories.show', $field->category_id) }}" class="btn-secondary">
            Kategoriyi Düzenle
        </a>
        <a href="{{ route('fields.edit', $field->id) }}" class="btn-secondary">
            Alanı Düzenle
        </a>
    </x-document-header>
    <x-document-panel>
        <div class="mb-2 flex items-center justify-between text-black/95">
            <h2 class="text-lg font-bold">Bilgiler</h2>
        </div>
        <ul>
            @foreach ($infos as $key => $info)
                <li class="flex items-center gap-x-1">
                    <span class="font-semibold">{{ $key }}:</span>
                    <p>{{ $info }}</p>
                </li>
            @endforeach
        </ul>
    </x-document-panel>
@endsection
