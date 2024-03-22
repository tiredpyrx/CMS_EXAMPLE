@extends('templates.admin')

@section('content')
    <x-document-header title="<i class='{{ '$category->icon' }}'></i> {{ $category->title }}">
        @if (auth()->user()->role == 'admin' || auth()->user()->role == 'editor')
            {{-- add button here --}}
        @endif
        <a href="{{ route('categories.edit', $category->id) }}" class="btn-secondary">
            Kategoriyi Düzenle
        </a>
        <a href="{{ route('posts.create', $category->id) }}" class="btn-secondary">
            Gönderi Ekle
        </a>
    </x-document-header>
    @if ($posts->count())
        <x-document-panel>
            <div class="mb-2 flex items-center justify-between text-black/95">
                <h2 class="tex-sm font-semibold">Kategori Gönderileri</h2>
            </div>
            <div class="relative border border-gray-100 shadow-md sm:rounded-lg">
                <div class="relative">
                    <div class="relative">
                        <table class="w-full text-left text-sm text-gray-500 rtl:text-right dark:text-gray-400">
                            <thead class="bg-gray-50 text-xs uppercase text-gray-700 dark:bg-gray-700 dark:text-gray-400">
                                <tr>
                                    <th scope="col" class="px-6 py-3">
                                        Gönderi Başlık
                                    </th>
                                    <th scope="col" class="px-6 py-3">
                                        Yazar İsmi
                                    </th>
                                    <th scope="col" class="px-6 py-3">
                                        Eklendiği Tarih
                                    </th>
                                    <th scope="col" class="px-6 py-3">
                                        Aktif
                                    </th>
                                    <th scope="col" class="px-4 py-2">
                                        <span class="sr-only">
                                            Seç
                                        </span>
                                        <div
                                            class="flex-column z-20 flex flex-wrap items-center justify-end space-y-4 dark:bg-gray-900 md:flex-row md:space-y-0">
                                            <div class="flex relative">

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
                                                                    onclick="deleteAllUnactivesGlobal('categories', 'post', '{{ $category->id }}')"
                                                                    class="text-red-500">Aktif olmayanları sil</button>
                                                            </li>
                                                            <li
                                                                class="whitespace-nowrap rounded-sm px-4 py-1 font-medium text-black/95 hover:bg-black/20">
                                                                <button onclick="selectActionDeleteAllSelected('posts')"
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
                                @foreach ($posts as $post)
                                    <tr @class([
                                        'border-b bg-white dark:border-gray-700 dark:bg-gray-800',
                                        'disabled' => !$post->active,
                                    ])>
                                        <th scope="row"
                                            class="whitespace-nowrap px-6 py-4 font-medium text-gray-900 dark:text-white">
                                            {{ $post->title }}
                                        </th>
                                        <td class="px-6 py-4">
                                            {{ $post->author_name }}
                                        </td>
                                        <td class="px-6 py-4">
                                            {{ $post->created_at_formatted }}
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="flex items-center">
                                                <label for="{{ $post->title . '-active-togglebox' }}" class="sr-only">
                                                    {{ $post->title . ' Aktif Seçim Kutusu' }}
                                                </label>
                                                <input data-key="title" data-value="{{ $post->title }}"
                                                    data-modelname="post" data-modelname_plural="categories"
                                                    @checked($post->active)
                                                    id="{{ $post->title . '-active-togglebox' }}" type="checkbox"
                                                    class="h-4 w-4 rounded border-gray-300 bg-gray-100 text-blue-600 focus:ring-2 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:ring-offset-gray-800 dark:focus:ring-blue-600">
                                            </div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="flex items-center justify-end">
                                                <input data-parent_node_name="TR" id="{{ $post->id . '-selectbox' }}"
                                                    type="checkbox" value=""
                                                    class="h-4 w-4 rounded border-gray-300 bg-gray-100 text-blue-600 focus:ring-2 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:ring-offset-gray-800 dark:focus:ring-blue-600">
                                            </div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="flex items-center justify-end">
                                                <div class="relative">
                                                    <button
                                                        class="document_dropdown_trigger flex h-9 w-9 cursor-pointer items-center justify-center rounded-full duration-200 hover:bg-black/25">
                                                        <i class="fa fa-ellipsis-vertical"></i>
                                                    </button>
                                                    <div
                                                        class="document_dropdown close-on-outside-click absolute bottom-1/2 right-full hidden translate-y-3/4 bg-gray-50 text-sm shadow">
                                                        <ul class="p-1">
                                                            <li
                                                                class="whitespace-nowrap rounded-sm border-b border-b-black/20 border-opacity-40 px-4 py-1 font-medium text-black/95 hover:bg-black/20">
                                                                <a
                                                                    href="{{ route('posts.show', $post->id) }}"
                                                                    class="w-full text-left text-green-500">Göster</a>
                                                            </li>
                                                            <li
                                                                class="whitespace-nowrap rounded-sm border-b border-b-black/20 border-opacity-40 px-4 py-1 font-medium text-black/95 hover:bg-black/20">
                                                                <a
                                                                    href="{{ route('posts.edit', $post->id) }}"
                                                                    class="w-full text-left text-green-500">Düzenle</a>
                                                            </li>
                                                            <li
                                                                class="whitespace-nowrap rounded-sm border-b border-b-black/20 border-opacity-40 px-4 py-1 font-medium text-black/95 hover:bg-black/20">
                                                                <button data-route_prefix="posts"
                                                                    data-route_suffix="duplicate" data-method="post"
                                                                    data-resource_unique="{{ $post->id }}"
                                                                    data-success_message="Gönderi başarıyla klonlandı!"
                                                                    data-error_message="Gönderi klonlanırken bir hata oluştu!"
                                                                    onclick="tableResourceAction(this)"
                                                                    class="w-full text-left text-orange-600">Klonla</button>
                                                            </li>
                                                            <li
                                                                class="whitespace-nowrap rounded-sm px-4 py-1 font-medium text-black/95 hover:bg-black/20">
                                                                <button data-route_prefix="posts"
                                                                    data-route_suffix="destroy" data-parent_node_name="TR"
                                                                    data-method="delete"
                                                                    data-resource_unique="{{ $post->id }}"
                                                                    data-success_message="Gönderi başarıyla silindi!"
                                                                    data-error_message="Gönderiyi silerken bir hata oluştu!"
                                                                    onclick="tableResourceAction(this)"
                                                                    class="w-full text-left text-red-500">Sil</button>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                </div>
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
                            <span class="font-semibold text-black/90 dark:text-gray-50">{{ $posts->count() }}</span> of
                            <span
                                class="font-semibold text-black/90 dark:text-gray-50">{{ modelGetAll('post')->count() }}</span></span>
                        @if (count($posts->links()->elements[0]) > 1)
                            <ul class="inline-flex h-8 -space-x-px text-sm rtl:space-x-reverse">
                                @foreach ($posts->links()->elements[0] as $idx => $link)
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
        <p class="mt-6 text-center font-semibold text-red-600">Bu kategorinin postu yok</p>
    @endif
@endsection
