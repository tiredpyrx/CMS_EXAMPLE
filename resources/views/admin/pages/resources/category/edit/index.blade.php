@extends('templates.admin')

@section('content')
    <x-document-header title="<i class='{{ '$category->icon' }}'></i> {{ $category->title }} Düzenle">
        @if (auth()->user()->role == 'admin' || auth()->user()->role == 'editor')
            {{-- add button here --}}
        @endif
        <a href="{{ route('categories.show', $category->id) }}" class="btn-secondary">
            Kategoriyi Gör
        </a>
        <a href="?open-jsmodal-with-category-infos" class="btn-secondary">
            Bilgiler
        </a>
        <form action="{{ route('categories.destroy', $category->id) }}" method="POST">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn-danger">Çöpe At</button>
        </form>
    </x-document-header>
    <x-document-panel>
        <form class="app-form" action="{{ route('categories.update', $category->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="grid grid-cols-12 gap-4">
                <div class="col-span-12">
                    <label default for="title">Başlık</label>
                    <input value="{{ $category->title }}" default id="title" name="title" type="text" />
                </div>
                <div class="col-span-6">
                    <label default for="icon">İkon</label>
                    <input value="{{ $category->icon }}" default id="icon" name="icon" type="text" />
                </div>
                <div class="col-span-6">
                    <label default for="view">Dosya</label>
                    <input value="{{ $category->view }}" default id="view" name="view" />
                </div>
                <div class="col-span-12">
                    <label default for="description">Açıklama</label>
                    <textarea default id="description" name="description">{{ $category->description }}</textarea>
                </div>
                <div class="col-span-12">
                    <div class="flex items-center gap-x-4">
                        <div class="mb-4 flex items-center">
                            <input @checked($category->have_details) name="have_details" id="have_details" type="checkbox"
                                value=""
                                class="h-4 w-4 rounded border-gray-300 bg-gray-100 text-blue-600 focus:ring-2 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:ring-offset-gray-800 dark:focus:ring-blue-600">
                            <label for="have_details"
                                class="ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">Detaya Sahip</label>
                        </div>
                        <div class="mb-4 flex items-center">
                            <input @checked($category->as_page) name="as_page" id="as_page" type="checkbox"
                                value=""
                                class="h-4 w-4 rounded border-gray-300 bg-gray-100 text-blue-600 focus:ring-2 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:ring-offset-gray-800 dark:focus:ring-blue-600">
                            <label for="as_page"
                                class="ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">Sayfayı Temsil Ediyor</label>
                        </div>
                        <div class="mb-4 flex items-center">
                            <input @checked($category->active) name="active" id="active" type="checkbox" value=""
                                class="h-4 w-4 rounded border-gray-300 bg-gray-100 text-blue-600 focus:ring-2 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:ring-offset-gray-800 dark:focus:ring-blue-600">
                            <label for="active"
                                class="ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">Aktif</label>
                        </div>
                    </div>
                </div>
                <div class="col-span-12">
                    <button class="btn-primary mt-6">
                        Güncelle
                    </button>
                </div>
            </div>
        </form>
    </x-document-panel>
    @if ($fields->count())
        <x-document-panel>
            <div class="relative overflow-x-auto border border-gray-100 shadow-md sm:rounded-lg">
                <div class="relative overflow-x-auto">
                    <div class="relative overflow-x-auto">
                        <table class="w-full text-left text-sm text-gray-500 rtl:text-right dark:text-gray-400">
                            <thead class="bg-gray-50 text-xs uppercase text-gray-700 dark:bg-gray-700 dark:text-gray-400">
                                <tr>
                                    <th scope="col" class="px-6 py-3">
                                        Alan Etiketi
                                    </th>
                                    <th scope="col" class="px-6 py-3">
                                        İşleyici
                                    </th>
                                    <th scope="col" class="px-6 py-3">
                                        Yazaar İsmi
                                    </th>
                                    <th scope="col" class="px-6 py-3">
                                        Aktif
                                    </th>
                                    <th scope="col" class="px-6 py-3">
                                        <span class="sr-only">
                                            Seç
                                        </span>
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($fields as $field)
                                    <tr class="border-b bg-white dark:border-gray-700 dark:bg-gray-800">
                                        <th scope="row"
                                            class="whitespace-nowrap px-6 py-4 font-medium text-gray-900 dark:text-white">
                                            {{ $field->label }}
                                        </th>
                                        <td class="px-6 py-4">
                                            {{ $field->handler }}
                                        </td>
                                        <td class="px-6 py-4">
                                            {{ $field->author_name }}
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="mb-4 flex items-center">
                                                <label for="{{ $field->handler . '-actice-togglebox' }}" class="sr-only">
                                                    {{ $category->title . ' Aktif Seçim Kutusu' }}
                                                </label>
                                                <input data-key="id" data-value="{{ $field->id }}"
                                                    data-modelname="field" data-modelname_plural="fields"
                                                    @checked($field->active) id="{{ $field->handler . '-actice-togglebox' }}"
                                                    type="checkbox"
                                                    class="h-4 w-4 rounded border-gray-300 bg-gray-100 text-blue-600 focus:ring-2 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:ring-offset-gray-800 dark:focus:ring-blue-600">
                                            </div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="mb-4 flex items-center">
                                                <input id="default-checkbox" type="checkbox" value=""
                                                    class="h-4 w-4 rounded border-gray-300 bg-gray-100 text-blue-600 focus:ring-2 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:ring-offset-gray-800 dark:focus:ring-blue-600">
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <nav class="flex-column flex flex-wrap items-center justify-between border-t border-gray-200 border-opacity-40 bg-gray-50 p-4 dark:bg-gray-800 md:flex-row"
                        aria-label="Table navigation">
                        <span
                            class="mb-4 block w-full text-sm font-normal text-black/90 dark:text-gray-50 md:mb-0 md:inline md:w-auto">Showing
                            <span class="font-semibold text-black/90 dark:text-gray-50">{{ $fields->count() }}</span> of
                            <span
                                class="font-semibold text-black/90 dark:text-gray-50">{{ $category->fields()->count() }}</span></span>
                        @if (count($paginationArray) > 1)
                            <ul class="inline-flex h-8 -space-x-px text-sm rtl:space-x-reverse">
                                @foreach ($paginationArray as $idx => $link)
                                    <li>
                                        <a href="{{ $link }}"
                                            class="flex h-8 items-center justify-center border border-gray-300 bg-white px-3 leading-tight text-gray-500 hover:bg-gray-100 hover:text-gray-700 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white">
                                            {{ $idx }}
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        @endif
                    </nav>
                </div>
            </div>
            <div class="mt-6 flex justify-end">
                <a href="{{ route('fields.create', ['modelName' => strtolower(class_basename($category)), 'modelId' => $category->id]) }}"
                    class="btn-secondary">
                    Alan Ekle
                </a>
            </div>
        </x-document-panel>
    @else
        <p class="mt-6 text-center font-semibold text-red-600">Bu kategorinin alanı yok</p>
    @endif
@endsection
