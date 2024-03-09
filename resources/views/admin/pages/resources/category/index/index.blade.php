@extends('templates.admin')

@section('content')
    <main>
        <x-document-header title="Tüm Kategoriler">
            @if (auth()->user()->role == 'admin' || auth()->user()->role == 'editor')
                {{-- add button here --}}
            @endif
            <a href="{{ route('categories.create') }}" class="btn-secondary">
                Kategori Ekle
            </a>
        </x-document-header>
        @if ($categories->count())
            <x-document-panel>
                <div class="relative overflow-x-auto border border-gray-100 shadow-md sm:rounded-lg">
                    <div
                        class="flex-column flex flex-wrap items-center justify-between space-y-4 bg-white p-4 dark:bg-gray-900 md:flex-row md:space-y-0">
                        <div class="relative">
                            <button id="user_index_dropdown_trigger" data-dropdown-toggle="dropdownAction"
                                class="inline-flex items-center rounded-lg border border-gray-300 bg-white px-3 py-1.5 text-sm font-medium text-gray-500 hover:bg-gray-100 focus:outline-none dark:border-gray-600 dark:bg-gray-800 dark:text-gray-400 dark:hover:border-gray-600 dark:hover:bg-gray-700"
                                type="button">
                                <span class="sr-only">Action button</span>
                                Action
                                <svg class="ms-2.5 h-2.5 w-2.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                    fill="none" viewBox="0 0 10 6">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                        stroke-width="2" d="m1 1 4 4 4-4" />
                                </svg>
                            </button>
                            <!-- Dropdown menu -->
                            <div id="user_index_dropdown_target"
                                class="close-on-outside-click absolute left-0 top-full z-10 hidden w-44 divide-y divide-gray-100 rounded-lg bg-white shadow dark:divide-gray-600 dark:bg-gray-700">
                                <ul class="py-1 text-sm text-gray-700 dark:text-gray-200"
                                    aria-labelledby="dropdownActionButton">
                                    <li>
                                        <button href="#"
                                            class="block w-full px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">
                                            <div class="relative flex items-center justify-between">
                                                <label class="cursor-pointer" for="">Görünüm 1</label>
                                                <input type="radio" class="absolute h-full w-full hidden" name="listing-layout-radio"
                                                    id="paginate">
                                            </div>
                                        </button>
                                    </li>
                                    <li>
                                        <button href="#"
                                            class="block w-full px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">
                                            <div class="relative flex items-center justify-between">
                                                <label class="cursor-pointer" for="">Görünüm 2</label>
                                                <input class="absolute h-full w-full hidden" type="radio" name="listing-layout-radio"
                                                    id="full">
                                            </div>
                                        </button>
                                    </li>
                                    <li>
                                        <button href="#"
                                            class="block w-full px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">
                                            <div class="relative flex items-center justify-between">
                                                <label class="cursor-pointer" for="">Görünüm 3</label>
                                                <input class="absolute h-full w-full hidden" type="radio" name="listing-layout-radio" id="boxes">
                                            </div>
                                        </button>
                                    </li>
                                </ul>
                                <div class="py-1">
                                    <a href="#"
                                        class="block px-4 py-2 text-sm text-red-500 hover:bg-gray-100 dark:text-red-500 dark:hover:bg-gray-600 dark:hover:text-red-600">Aktif Olmayanları Sil</a>
                                </div>
                            </div>
                        </div>
                        <label for="table-search" class="sr-only">Search</label>
                        <div class="relative">
                            <div
                                class="rtl:inset-r-0 pointer-events-none absolute inset-y-0 start-0 flex items-center ps-3">
                                <svg class="h-4 w-4 text-gray-500 dark:text-gray-400" aria-hidden="true"
                                    xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                        stroke-width="2" d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z" />
                                </svg>
                            </div>
                            <input type="text" id="table-search-users"
                                class="block w-80 rounded-lg border border-gray-300 bg-gray-50 p-2 ps-10 text-sm text-gray-900 focus:border-blue-500 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:placeholder-gray-400 dark:focus:border-blue-500 dark:focus:ring-blue-500"
                                placeholder="Search for categories">
                        </div>
                    </div>
                    <div class="relative overflow-x-auto">
                        <div class="relative overflow-x-auto">
                            <table class="w-full text-left text-sm text-gray-500 rtl:text-right dark:text-gray-400">
                                <thead
                                    class="bg-gray-50 text-xs uppercase text-gray-700 dark:bg-gray-700 dark:text-gray-400">
                                    <tr>
                                        <th scope="col" class="px-6 py-3">
                                            Kategori Başlık
                                        </th>
                                        <th scope="col" class="px-6 py-3">
                                            Yazaar İsmi
                                        </th>
                                        <th scope="col" class="px-6 py-3">
                                            Gönderi sayısı
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
                                    @foreach ($categories as $category)
                                        <tr class="border-b bg-white dark:border-gray-700 dark:bg-gray-800">
                                            <th scope="row"
                                                class="whitespace-nowrap px-6 py-4 font-medium text-gray-900 dark:text-white">
                                                {{ $category->title }}
                                            </th>
                                            <td class="px-6 py-4">
                                                {{ $category->author_name }}
                                            </td>
                                            <td class="px-6 py-4">
                                                {{ $category->posts_count }}
                                            </td>
                                            <td class="px-6 py-4">
                                                <div class="mb-4 flex items-center">
                                                    <label for="{{ $category->title.'-actice-togglebox' }}" class="sr-only">
                                                        {{ $category->title.' Aktif Seçim Kutusu' }}
                                                    </label>
                                                    <input data-key="title" data-value="{{ $category->title }}" data-modelname="category" data-modelname_plural="categories" @checked($category->active) id="{{ $category->title.'-actice-togglebox' }}" type="checkbox"
                                                        class="h-4 w-4 rounded border-gray-300 bg-gray-100 text-blue-600 focus:ring-2 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:ring-offset-gray-800 dark:focus:ring-blue-600">
                                                </div>
                                            </td>
                                            <td class="px-6 py-4">
                                                <div class="mb-4 flex items-center">
                                                    <input id="{{ $category->title.' -selectbox' }}" type="checkbox" value=""
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
                                <span
                                    class="font-semibold text-black/90 dark:text-gray-50">{{ $categories->count() }}</span>
                                of <span
                                    class="font-semibold text-black/90 dark:text-gray-50">{{ getAll('category')->count() }}</span></span>
                            @if (count($categories->links()->elements[0]) > 1)
                                <ul class="inline-flex h-8 -space-x-px text-sm rtl:space-x-reverse">
                                    @foreach ($categories->links()->elements[0] as $idx => $link)
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
            </x-document-panel>
        @else
            <p class="mt-6 text-center font-semibold text-red-600">Bu kategorinin alanı yok</p>
        @endif
    </main>
@endsection
