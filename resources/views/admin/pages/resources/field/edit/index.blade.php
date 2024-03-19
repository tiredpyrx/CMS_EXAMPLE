@extends('templates.admin')

@section('content')
    <x-document-header title="{{ $category->title . ' Alan ' . $field->title . ' Düzenle' }}">
        <a href="{{ route('fields.show', $field->id) }}" class="btn-secondary">
            Alanı Gör
        </a>
    </x-document-header>
    <x-document-panel>
        <form class="app-form" action="{{ route('fields.update', $field->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="grid grid-cols-12 gap-4">
                <div class="col-span-6">
                    <label default for="label">Etiket</label>
                    <input default id="label" value="{{ $field->label }}" name="label" type="text" />
                </div>
                <div class="col-span-6">
                    <label default for="handler">İşleyici*</label>
                    <input default id="handler" value="{{ $field->handler }}" name="handler" type="text" />
                </div>
                <div class="col-span-6">
                    <label default for="placeholder">Hayalet Yazı</label>
                    <input default id="placeholder" {{ $field->placeholder }} name="placeholder" />
                </div>
                <div class="col-span-6">
                    <label default for="value">Varsayılan Değer</label>
                    <input default id="value" value="{{ $field->value }}" name="value" />
                </div>
                <div class="col-span-6">
                    <label default for="type">Alan Tipi</label>
                    <input value="{{ $field->type }}" default id="type" name="type" />
                </div>
                <div class="col-span-6 flex flex-col justify-start gap-y-6">
                    <label for="column" class="block text-sm font-medium text-gray-900 dark:text-white">
                        Sütun Uzunluğu
                        <div class="inline-flex gap-x-0.5 text-sm font-semibold">
                            <span id="field-column-range-display"></span>
                            <span class="tracking-tighter">/ 12</span>
                        </div>
                    </label>
                    <input name="column" id="field-column-range" type="range" step="1" value="{{ $field->column }}"
                        min="3" max="12"
                        class="h-2 w-full cursor-pointer appearance-none rounded-lg bg-gray-200 dark:bg-gray-700">
                </div>
                <div class="col-span-12">
                    <label default for="description">Açıklama</label>
                    <textarea default id="description" name="description">{{ $field->description }}</textarea>
                </div>
                <div class="col-span-12">
                    <div class="flex items-center gap-x-4">
                        <div class="flex items-center">
                            <input name="active" id="active" @checked($field->active) type="checkbox"
                                class="h-4 w-4 rounded border-gray-300 bg-gray-100 text-blue-600 focus:ring-2 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:ring-offset-gray-800 dark:focus:ring-blue-600">
                            <label for="active"
                                class="ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">Aktif</label>
                        </div>
                        <div class="flex items-center">
                            <input name="required" id="required" @checked($field->required) type="checkbox"
                                class="h-4 w-4 rounded border-gray-300 bg-gray-100 text-blue-600 focus:ring-2 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:ring-offset-gray-800 dark:focus:ring-blue-600">
                            <label for="required"
                                class="ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">Zorunlu</label>
                        </div>
                    </div>
                </div>
                <div class="col-span-12">
                    <button class="btn-primary">
                        Yeni Ekle
                    </button>
                </div>
            </div>
        </form>
    </x-document-panel>
@endsection

<script>
    document.addEventListener("DOMContentLoaded", () => {
        document.getElementById("field-column-range-display").innerText = document.getElementById(
            "field-column-range").value;
        document.getElementById("field-column-range").addEventListener("input", (e) => {
            document.getElementById("field-column-range-display").innerText = e.target.value;
        })
    })
</script>
