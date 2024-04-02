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
                        <div class="flex items-center">
                            <input @checked($category->have_details) name="have_details" id="have_details" type="checkbox"
                                value=""
                                class="h-4 w-4 rounded border-gray-300 bg-gray-100 text-blue-600 focus:ring-2 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:ring-offset-gray-800 dark:focus:ring-blue-600">
                            <label for="have_details"
                                class="ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">Detaya Sahip</label>
                        </div>
                        <div class="flex items-center">
                            <input @checked($category->as_page) name="as_page" id="as_page" type="checkbox" value=""
                                class="h-4 w-4 rounded border-gray-300 bg-gray-100 text-blue-600 focus:ring-2 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:ring-offset-gray-800 dark:focus:ring-blue-600">
                            <label for="as_page" class="ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">Sayfa
                                Temsil</label>
                        </div>
                        <div class="flex items-center">
                            <input @checked($category->active) name="active" id="active" type="checkbox" value=""
                                class="h-4 w-4 rounded border-gray-300 bg-gray-100 text-blue-600 focus:ring-2 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:ring-offset-gray-800 dark:focus:ring-blue-600">
                            <label for="active"
                                class="ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">Aktif</label>
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
    @if ($fields->count())
        <x-document-panel>
            <div class="mb-2 flex items-center justify-between text-black/95">
                <h2 class="tex-sm font-semibold">Kategori Alanları</h2>
            </div>
            <div class="relative border border-gray-100 shadow-md sm:rounded-lg">
                <div class="relative">
                    <div class="relative">
                        <table class="w-full text-left text-sm text-gray-500 rtl:text-right dark:text-gray-400">
                            <thead class="bg-gray-50 text-xs uppercase text-gray-700 dark:bg-gray-700 dark:text-gray-400">
                                <tr>
                                    <th scope="col" class="px-4 py-2">
                                        Alan Etiketi
                                    </th>
                                    <th scope="col" class="px-4 py-2">
                                        İşleyici
                                    </th>
                                    <th scope="col" class="px-4 py-2">
                                        Yazar İsmi
                                    </th>
                                    <th scope="col" class="px-4 py-2">
                                        Aktif
                                    </th>
                                    <th scope="col" class="px-4 py-2">
                                        <span class="sr-only">
                                            Seç
                                        </span>
                                        <div
                                            class="flex-column z-20 flex flex-wrap items-center justify-end space-y-4 dark:bg-gray-900 md:flex-row md:space-y-0">
                                            <div class="relative flex">

                                                <div
                                                    class="document_dropdown_trigger flex h-9 w-9 items-center justify-center rounded-full duration-200 hover:bg-black/25">
                                                    <i class="fa fa-ellipsis-vertical"></i>
                                                </div>
                                                <div
                                                    class="document_dropdown close-on-outside-click absolute bottom-1/2 right-full hidden translate-y-3/4 bg-gray-50 text-sm shadow">
                                                    <ul class="p-1">
                                                        <li
                                                            class="whitespace-nowrap rounded-sm border-b border-b-black/20 border-opacity-40 px-4 py-1 font-medium text-black/95 hover:bg-black/20">
                                                            <form action="" method="POST">
                                                                @csrf
                                                                @method('PUT')
                                                                <button class="text-gray-800">Seçilenlerin
                                                                    aktifini
                                                                    aç</button>
                                                            </form>
                                                        </li>
                                                        <li
                                                            class="whitespace-nowrap rounded-sm border-b border-b-black/20 border-opacity-40 px-4 py-1 font-medium text-black/95 hover:bg-black/20">
                                                            <form action="" method="POST">
                                                                @csrf
                                                                @method('PUT')
                                                                <button class="text-gray-800">Seçilenlerin
                                                                    aktifini
                                                                    kapa</button>
                                                            </form>
                                                        </li>
                                                        <li
                                                            class="whitespace-nowrap rounded-sm border-b border-b-black/20 border-opacity-40 px-4 py-1 font-medium text-black/95 hover:bg-black/20">
                                                            <form action="" method="POST">
                                                                @csrf
                                                                @method('PUT')
                                                                <button class="text-gray-800">Sırala</button>
                                                            </form>
                                                        </li>
                                                        <li
                                                            class="whitespace-nowrap rounded-sm border-b border-b-black/20 border-opacity-40 px-4 py-1 font-medium text-black/95 hover:bg-black/20">
                                                            <button
                                                                onclick="deleteAllUnactivesGlobal('categories', 'field', '{{ $category->id }}')"
                                                                class="text-red-500">Aktif olmayanları
                                                                sil</button>
                                                        </li>
                                                        <li
                                                            class="whitespace-nowrap rounded-sm px-4 py-1 font-medium text-black/95 hover:bg-black/20">
                                                            <button onclick="selectActionDeleteAllSelected('fields')"
                                                                class="w-full text-left text-red-500">Seçilenleri
                                                                sil</button>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    </th>
                                    <th scope="col" class="px-4 py-2">
                                        <span class="sr-only">
                                            Düzenle
                                        </span>
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($fields as $field)
                                    <tr @class([
                                        'border-b bg-white dark:border-gray-700 dark:bg-gray-800',
                                        'disabled' => !$field->active,
                                    ])>
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
                                            @unless (in_array($field->handler, $notEditableFields))
                                                <div class="flex items-center">
                                                    <label for="{{ $field->handler . '-active-togglebox' }}" class="sr-only">
                                                        {{ $category->title . ' Aktif Seçim Kutusu' }}
                                                    </label>
                                                    <input data-key="id" data-value="{{ $field->id }}"
                                                        data-modelname_human="Alan" data-modelname="field" data-modelname_plural="fields"
                                                        @checked($field->active)
                                                        id="{{ $field->handler . '-active-togglebox' }}" type="checkbox"
                                                        class="h-4 w-4 rounded border-gray-300 bg-gray-100 text-blue-600 focus:ring-2 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:ring-offset-gray-800 dark:focus:ring-blue-600">
                                                </div>
                                            @else
                                                <input disabled checked onclick="return false" type="checkbox"
                                                    class="h-4 w-4 rounded border-gray-300 bg-gray-100 text-blue-600 focus:ring-2 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:ring-offset-gray-800 dark:focus:ring-blue-600">
                                            @endunless
                                        </td>
                                        <td class="flex h-[70px] items-center justify-end px-6 py-4">
                                            @unless (in_array($field->handler, $notEditableFields))
                                                <input type="checkbox"
                                                    class="h-4 w-4 rounded border-gray-300 bg-gray-100 text-blue-600 focus:ring-2 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:ring-offset-gray-800 dark:focus:ring-blue-600">
                                            @else
                                                <input disabled onclick="return false" type="checkbox"
                                                class="h-4 w-4 rounded border-gray-300 bg-gray-100 text-blue-600 focus:ring-2 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:ring-offset-gray-800 dark:focus:ring-blue-600">
                                            @endunless
                                        </td>
                                        <td class="px-6 py-4">
                                            @unless (in_array($field->handler, $notEditableFields))
                                                <div class="flex items-center justify-end">
                                                    <div class="relative">
                                                        <button
                                                            class="@unless (in_array($field->handler, $notEditableFields)) document_dropdown_trigger @endunless flex h-9 w-9 cursor-pointer items-center justify-center rounded-full duration-200 hover:bg-black/25">
                                                            <i class="fa fa-ellipsis-vertical"></i>
                                                        </button>
                                                        <div
                                                            class="document_dropdown close-on-outside-click absolute bottom-1/2 right-full hidden translate-y-3/4 bg-gray-50 text-sm shadow">
                                                            <ul class="p-1">
                                                                <li
                                                                    class="whitespace-nowrap rounded-sm border-b border-b-black/20 border-opacity-40 px-4 py-1 font-medium text-black/95 hover:bg-black/20">
                                                                    <a href="{{ route('fields.show', $field->id) }}"
                                                                        class="w-full text-left text-green-500">Göster</a>
                                                                </li>
                                                                <li
                                                                    class="whitespace-nowrap rounded-sm border-b border-b-black/20 border-opacity-40 px-4 py-1 font-medium text-black/95 hover:bg-black/20">
                                                                    <a href="{{ route('fields.edit', $field->id) }}"
                                                                        class="w-full text-left text-green-500">Düzenle</a>
                                                                </li>
                                                                <li
                                                                    class="whitespace-nowrap rounded-sm border-b border-b-black/20 border-opacity-40 px-4 py-1 font-medium text-black/95 hover:bg-black/20">
                                                                    <button data-route_prefix="fields"
                                                                        data-route_suffix="duplicate" data-method="post"
                                                                        data-resource_unique="{{ $field->id }}"
                                                                        data-success_message="Alan başarıyla klonlandı!"
                                                                        data-error_message="Alan klonlanırken bir hata oluştu!"
                                                                        onclick="tableResourceAction(this)"
                                                                        class="w-full text-left text-orange-600">Klonla</button>
                                                                </li>
                                                                <li
                                                                    class="whitespace-nowrap rounded-sm px-4 py-1 font-medium text-black/95 hover:bg-black/20">
                                                                    <button data-route_prefix="fields"
                                                                        data-route_suffix="destroy" data-parent_node_name="TR"
                                                                        data-method="delete"
                                                                        data-resource_unique="{{ $field->id }}"
                                                                        data-success_message="Alan başarıyla silindi!"
                                                                        data-error_message="Alanı silerken bir hata oluştu!"
                                                                        onclick="tableResourceAction(this)"
                                                                        class="w-full text-left text-red-500">Sil</button>
                                                                </li>
                                                            </ul>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endunless
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <nav class="flex-column z-0 flex flex-wrap items-center justify-between border-t border-gray-200 border-opacity-40 bg-gray-50 p-4 dark:bg-gray-800 md:flex-row"
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
                <a href="{{ route('fields.create', $category->id) }}"
                    class="btn-secondary">
                    Alan Ekle
                </a>
            </div>
        </x-document-panel>
    @else
        <p class="mt-6 text-center font-semibold text-red-600">Bu kategorinin alanı yok</p>
        <div class="flex justify-center">
            <div class="mt-2 flex justify-end">
                <a href="{{ route('fields.create', $category->id) }}"
                    class="btn-secondary">
                    Alan Ekle
                </a>
            </div>

        </div>
    @endif
@endsection

@push('js')
    <script type="module" src="{{ mix('./resources/admin/js/pages/category/index.js') }}"></script>
@endpush
