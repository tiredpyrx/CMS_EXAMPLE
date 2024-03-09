@extends('templates.admin')

@section('content')
    <x-document-header title="Kategori Ekle">
        <a href="{{ route('categories.index') }}" class="btn-secondary">Tüm Kategoriler</a>
    </x-document-header>
    <x-document-panel>
        <form class="app-form" action="{{ route('categories.store') }}" method="POST">
            @csrf
            <div class="grid grid-cols-12 gap-4">
                <div class="col-span-12">
                    <label default for="title">Başlık</label>
                    <input default id="title" name="title" type="text" />
                </div>
                <div class="col-span-6">
                    <label default for="icon">İkon</label>
                    <input default id="icon" name="icon" type="text" />
                </div>
                <div class="col-span-6">
                    <label default for="view">Dosya</label>
                    <input default id="view" name="view" />
                </div>
                <div class="col-span-12">
                    <label default for="description">Açıklama</label>
                    <textarea default id="description" name="description"></textarea>
                </div>
                {{-- <div class="col-span-4">
                    <label for="column" class="mb-2 block text-sm font-medium text-gray-900 dark:text-white">Sütun Büyüklüğü</label>
                    <input name="column" id="column" type="range" value="6" min="3" max="12"
                        class="h-2 w-full cursor-pointer appearance-none rounded-lg bg-gray-200 dark:bg-gray-700">
                </div> --}}
                <div class="col-span-12">
                    <div class="flex items-center gap-x-4">
                        <div class="flex items-center mb-4">
                            <input name="have_details" id="have_details" type="checkbox" value="" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                            <label for="have_details" class="ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">Detaya Sahip</label>
                        </div>
                        <div class="flex items-center mb-4">
                            <input name="direct_access" id="direct_access" type="checkbox" value="" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                            <label for="direct_access" class="ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">Direkt Kategori</label>
                        </div>
                        <div class="flex items-center mb-4">
                            <input checked name="active" id="active" type="checkbox" value="" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                            <label for="active" class="ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">Aktif</label>
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
