@extends('templates.admin')

@section('content')
    <x-document-header title="{{ $category->title . ' Alan ' . $field->title . ' Düzenle' }}">
        <a href="{{ route('categories.edit', $field->category_id) }}" class="btn-secondary">
            Kategoriyi Düzenle
        </a>
        <a href="{{ route('fields.show', $field->id) }}" class="btn-secondary">
            Alan Bilgileri
        </a>
    </x-document-header>
    <x-document-panel>
        <form class="app-form" action="{{ route('fields.update', $field->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="grid grid-cols-12 gap-4">
                <div class="grid-item col-span-6">
                    <label default for="label">Etiket</label>
                    <input data-value="{{ $field->label }}" value="{{ $field->label }}" default id="label"
                        name="label" type="text" />
                </div>
                <div class="grid-item col-span-6">
                    <label default for="handler">İşleyici*</label>
                    <input sluggable="1" data-value="{{ $field->handler }}" value="{{ $field->handler }}" default id="handler"
                        name="handler" type="text" />
                </div>
                <div class="grid-item col-span-6">
                    <label default for="placeholder">Hayalet Yazı</label>
                    <input data-value="{{ $field->placeholder }}" value="{{ $field->placeholder }}" default id="placeholder"
                        name="placeholder" />
                </div>
                <div class="grid-item col-span-6">
                    <label default for="value">Varsayılan Değer</label>
                    <input sluggable="{{ $field->sluggable }}" data-value="{{ $field->value }}"
                        value="{{ $field->value }}" default id="value" name="value" />
                </div>
                <div class="grid-item col-span-6">
                    <label default for="prefix">Önek</label>
                    <input @readonly($field->url) @class(['disabled' => $field->url]) data-value="{{ $field->prefix }}"
                        value="{{ $field->prefix }}" default id="prefix" name="prefix" />
                </div>
                <div class="grid-item col-span-6">
                    <label default for="suffix">Sonek</label>
                    <input data-value="{{ $field->suffix }}" value="{{ $field->suffix }}" default id="suffix"
                        name="suffix" />
                </div>
                <div class="grid-item col-span-6">
                    <label default for="min_value">Minimum Değer</label>
                    <input data-value="{{ $field->min_value }}" value="{{ $field->min_value }}" default id="min_value"
                        type="number" name="min_value" />
                </div>
                <div class="grid-item col-span-6">
                    <label default for="max_value">Maksimum Değer</label>
                    <input data-value="{{ $field->max_value }}" value="{{ $field->max_value }}" default id="max_value"
                        type="number" name="max_value" />
                </div>
                <div class="grid-item col-span-6">
                    <label default for="step">Artış Aralığı</label>
                    <input data-value="{{ $field->step }}" value="{{ $field->step }}" default id="step"
                        name="step" type="number" />
                </div>
                <div class="grid-item col-span-6">
                    <label default for="type">Alan Tipi</label>
                    <select default name="type" id="type">
                        @foreach ($typesWithLabels as $typeWithLabel)
                            <option @selected($field->type === $typeWithLabel['value']) value="{{ $typeWithLabel['value'] }}">
                                {{ $typeWithLabel['label'] }}
                            </option>
                        @endforeach
                    </select>
                </div>
                {{-- <div class="col-span-12 grid-item">
                    <div id="field_options_container" data-option_handler="options[]">
                    </div>
                </div> --}}
                <div class="grid-item col-span-6">
                    <label default for="image">Görsel</label>
                    <input default id="image" type="file" accept=".jpg,.jpeg,.png,.webp,.avif,.svg" name="image" />
                </div>
                <div class="grid-item col-span-6">
                    <label default for="images">Görseller</label>
                    <input default id="images" type="file" accept=".jpg,.jpeg,.png,.webp,.avif,.svg" name="images[]"
                        multiple />
                </div>
                <div class="grid-item col-span-12">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label default for="image_width">Görsel Genişlik</label>
                            <input default id="image_width" type="number" name="image_width" />
                        </div>
                        <div>
                            <label default for="image_height">Görsel Yükseklik</label>
                            <input default id="image_height" type="number" name="image_height" />
                        </div>
                    </div>
                </div>
                <div class="grid-item col-span-6 flex flex-col justify-start gap-y-6">
                    <label for="field-column-range" class="block text-sm font-medium text-gray-900 dark:text-white">
                        Sütun Uzunluğu
                        <div class="inline-flex gap-x-0.5 text-sm font-semibold">
                            <span id="field-column-range-display">{{ $field->column }}</span>
                            <span class="tracking-tighter">/ 12</span>
                        </div>
                    </label>
                    <input data-value="{{ $field->column }}" name="column" id="field-column-range" type="range"
                        value="{{ $field->column }}" min="3" max="12"
                        class="h-2 w-full cursor-pointer appearance-none rounded-lg bg-gray-200 dark:bg-gray-700">
                </div>
                <div class="grid-item col-span-12">
                    <label default for="description">Açıklama</label>
                    <textarea data-value="{{ $field->description }}" default id="description" name="description">{{ $field->description }}</textarea>
                </div>
                <div class="col-span-12">
                    <div class="flex items-center gap-x-4">
                        <div class="flex items-center">
                            <input @checked($field->active) name="active" id="active" type="checkbox"
                                value=""
                                class="h-4 w-4 rounded border-gray-300 bg-gray-100 text-blue-600 focus:ring-2 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:ring-offset-gray-800 dark:focus:ring-blue-600">
                            <label for="active"
                                class="ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">Aktif</label>
                        </div>
                        <div class="flex items-center">
                            <input @checked($field->required) name="required" id="required" type="checkbox"
                                value=""
                                class="h-4 w-4 rounded border-gray-300 bg-gray-100 text-blue-600 focus:ring-2 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:ring-offset-gray-800 dark:focus:ring-blue-600">
                            <label for="required"
                                class="ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">Zorunlu</label>
                        </div>
                        <div class="grid-item flex items-center">
                            <input @checked($field->url) name="url" id="url" type="checkbox"
                                value=""
                                class="h-4 w-4 rounded border-gray-300 bg-gray-100 text-blue-600 focus:ring-2 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:ring-offset-gray-800 dark:focus:ring-blue-600">
                            <label for="yrl" class="ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">URL
                                Alanı</label>
                        </div>
                        <div class="grid-item flex items-center">
                            <input @checked($field->sluggable) name="sluggable" id="sluggable" type="checkbox"
                                value=""
                                class="h-4 w-4 rounded border-gray-300 bg-gray-100 text-blue-600 focus:ring-2 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:ring-offset-gray-800 dark:focus:ring-blue-600">
                            <label for="sluggable" class="ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">Slug
                                Alanı</label>
                        </div>
                    </div>
                </div>
                <div class="col-span-12">
                    <button class="btn-primary">
                        Güncelle
                    </button>
                </div>
            </div>
        </form>
    </x-document-panel>
    @if ($fieldFilesCount && $fieldHasAnyFileWithSource)
        @if ($field->type === 'image')
            <div id="image-container">
                <x-document-panel>
                    <span class="mb-4 block text-sm font-semibold">Alan Görseli</span>
                    <div class="grid">
                        <img class="max-h-[400px] w-full object-cover" src="{{ url($field->firstFile()?->source) }}">
                    </div>
                </x-document-panel>
            </div>
        @elseif ($field->type === 'images')
            <div id="images-container">
                <x-document-panel>
                    <span class="mb-4 block text-sm font-semibold">Alan Görselleri</span>
                    <div class="grid grid-cols-4 gap-4">
                        @foreach ($field->files as $image)
                            <x-document-panel class="relative">
                                <button onclick="deleteFile('{{ $image->id }}')"
                                    class="absolute right-2 top-2 h-6 w-6 rounded-full text-gray-800 hover:bg-gray-50"><i
                                        class="fa fa-xmark"></i></button>
                                <div class="mt-1">
                                    <img class="max-h-[400px] w-full object-cover" src="{{ url($image->source) }}">
                                </div>
                            </x-document-panel>
                        @endforeach
                    </div>
                </x-document-panel>
            </div>
        @endif
    @endif
@endsection

@push('js')
    <script type="module" src="{{ mix('./resources/admin/js/pages/field/index.js') }}"></script>
    <script>
        document.addEventListener("DOMContentLoaded", () => {
            document.getElementById("field-column-range-display").innerText = document.getElementById(
                "field-column-range").value;
            document.getElementById("field-column-range").addEventListener("input", (e) => {
                document.getElementById("field-column-range-display").innerText = e.target.value;
            })
        })
    </script>
@endpush
