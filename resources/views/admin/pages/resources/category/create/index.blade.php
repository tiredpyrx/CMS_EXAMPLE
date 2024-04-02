@extends('templates.admin')

@section('content')
    <x-document-header title="Kategori Ekle">
        <a href="{{ route('categories.index') }}" class="btn-secondary">Tüm Kategoriler</a>
    </x-document-header>
    <x-document-panel>
        @include('admin.pages.resources.category.form.index')
    </x-document-panel>
@endsection


@push('js')
    <script type="module" src="{{ mix('./resources/admin/js/pages/category/index.js') }}"></script>
@endpush
