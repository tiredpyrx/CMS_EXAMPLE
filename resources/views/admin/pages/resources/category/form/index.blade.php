<form class="app-form" action="{{ route('categories.store') }}" method="POST">
    @csrf
    <div class="grid grid-cols-12 gap-4">
        <div class="col-span-12">
            <label default for="title">Başlık*</label>
            <input value="{{ old('title') }}" default id="title" name="title" type="text" />
        </div>
        <div class="col-span-6">
            <label default for="icon">İkon</label>
            <input value="{{ old('icon') }}" default id="icon" name="icon" type="text" />
        </div>
        <div class="col-span-6">
            <label default for="view">Dosya</label>
            <input value="{{ old('view') }}" default id="view" name="view" />
        </div>
        <div class="col-span-12">
            <label default for="description">Açıklama</label>
            <textarea default id="description" name="description">{{ old('description') }}</textarea>
        </div>
        <div class="col-span-12">
            <div class="flex items-center gap-x-4">
                <div class="mb-4 flex items-center">
                    <input value="{{ old('have_details') }}" name="have_details" id="have_details" type="checkbox"
                        class="h-4 w-4 rounded border-gray-300 bg-gray-100 text-blue-600 focus:ring-2 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:ring-offset-gray-800 dark:focus:ring-blue-600">
                    <label for="have_details" class="ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">Detaya
                        Sahip</label>
                </div>
                <div class="mb-4 flex items-center">
                    <input value="{{ old('as_page') }}" name="as_page" id="as_page" type="checkbox"
                        class="h-4 w-4 rounded border-gray-300 bg-gray-100 text-blue-600 focus:ring-2 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:ring-offset-gray-800 dark:focus:ring-blue-600">
                    <label for="as_page" class="ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">Sayfa
                        Temsil</label>
                </div>
                <div class="mb-4 flex items-center">
                    <input @checked(old('active') ? old('active') : true) name="active" id="active" type="checkbox"
                        class="h-4 w-4 rounded border-gray-300 bg-gray-100 text-blue-600 focus:ring-2 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:ring-offset-gray-800 dark:focus:ring-blue-600">
                    <label for="active"
                        class="ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">Aktif</label>
                </div>
            </div>
        </div>
        <div class="col-span-12">
            <button class="btn-primary">
                Ekle
            </button>
        </div>
    </div>
</form>
