<button data-drawer-target="default-sidebar" data-drawer-toggle="default-sidebar" aria-controls="default-sidebar"
    type="button"
    class="fixed bottom-1 left-1 ms-3 mt-2 inline-flex items-center rounded-lg bg-black p-2 text-sm text-gray-500 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-200 dark:text-gray-400 dark:hover:bg-gray-700 dark:focus:ring-gray-600 sm:hidden">
    <span class="sr-only">Open sidebar</span>
    <svg class="h-6 w-6" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
        <path clip-rule="evenodd" fill-rule="evenodd"
            d="M2 4.75A.75.75 0 012.75 4h14.5a.75.75 0 010 1.5H2.75A.75.75 0 012 4.75zm0 10.5a.75.75 0 01.75-.75h7.5a.75.75 0 010 1.5h-7.5a.75.75 0 01-.75-.75zM2 10a.75.75 0 01.75-.75h14.5a.75.75 0 010 1.5H2.75A.75.75 0 012 10z">
        </path>
    </svg>
</button>


<aside id="app_sidebar"
    class="hide-scrollbar fixed transition-all duration-500 left-0 top-0 z-40 h-screen w-64 -translate-x-full sm:translate-x-0"
    aria-label="Sidebar">
    <div class="hide-scrollbar h-full overflow-y-auto bg-gray-50 px-3 py-4 dark:bg-gray-800">
        <nav class="flex h-full flex-col justify-between">

            <div class="my-4 ml-2 text-3xl font-bold text-gray-950 dark:text-gray-100">
                <h2>
                    <a href="/">
                        LOGO
                    </a>
                </h2>
            </div>
            <ul class="space-y-2 font-medium">
                <li>
                    <a href="{{ route('dashboard') }}"
                        class="group flex items-center rounded-lg p-2 text-gray-900 hover:bg-gray-100 dark:text-white dark:hover:bg-gray-700">
                        <div
                            class="flex h-5 w-5 flex-shrink-0 items-center text-xl text-gray-500 transition duration-75 group-hover:text-gray-900 dark:text-gray-400 dark:group-hover:text-white">
                            <i class="fa fa-home"></i>
                        </div>
                        <span class="ms-3">Panel</span>
                    </a>
                </li>
            </ul>
            <button id="sidebar-advanced-trigger"
                class="group flex w-full items-center rounded-lg p-2 text-gray-900 hover:bg-gray-100 dark:text-white dark:hover:bg-gray-700">
                <svg class="h-5 w-5 text-gray-500 transition duration-75 group-hover:text-gray-900 dark:text-gray-400 dark:group-hover:text-white"
                    aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 22 21">
                    <path
                        d="M16.975 11H10V4.025a1 1 0 0 0-1.066-.998 8.5 8.5 0 1 0 9.039 9.039.999.999 0 0 0-1-1.066h.002Z" />
                    <path
                        d="M12.5 0c-.157 0-.311.01-.565.027A1 1 0 0 0 11 1.02V10h8.975a1 1 0 0 0 1-.935c.013-.188.028-.374.028-.565A8.51 8.51 0 0 0 12.5 0Z" />
                </svg>
                <span class="ms-3">Advanced</span>
            </button>
            <ul id="sidebar-advanced-target" class="ml-2 hidden space-y-2 py-2 text-sm font-medium">
                <li>
                    <a href="#"
                        class="group flex items-center rounded-lg p-2 text-gray-900 hover:bg-gray-100 dark:text-white dark:hover:bg-gray-700">
                        <div
                            class="flex h-5 w-5 flex-shrink-0 items-center text-xl text-gray-500 transition duration-75 group-hover:text-gray-900 dark:text-gray-400 dark:group-hover:text-white">
                            <i class="fa fa-gear"></i>
                        </div>
                        <span class="ms-3">Ayarlar</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('categories.index') }}"
                        class="group flex items-center rounded-lg p-2 text-gray-900 hover:bg-gray-100 dark:text-white dark:hover:bg-gray-700">
                        <svg class="h-5 w-5 flex-shrink-0 text-gray-500 transition duration-75 group-hover:text-gray-900 dark:text-gray-400 dark:group-hover:text-white"
                            aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor"
                            viewBox="0 0 18 18">
                            <path
                                d="M6.143 0H1.857A1.857 1.857 0 0 0 0 1.857v4.286C0 7.169.831 8 1.857 8h4.286A1.857 1.857 0 0 0 8 6.143V1.857A1.857 1.857 0 0 0 6.143 0Zm10 0h-4.286A1.857 1.857 0 0 0 10 1.857v4.286C10 7.169 10.831 8 11.857 8h4.286A1.857 1.857 0 0 0 18 6.143V1.857A1.857 1.857 0 0 0 16.143 0Zm-10 10H1.857A1.857 1.857 0 0 0 0 11.857v4.286C0 17.169.831 18 1.857 18h4.286A1.857 1.857 0 0 0 8 16.143v-4.286A1.857 1.857 0 0 0 6.143 10Zm10 0h-4.286A1.857 1.857 0 0 0 10 11.857v4.286c0 1.026.831 1.857 1.857 1.857h4.286A1.857 1.857 0 0 0 18 16.143v-4.286A1.857 1.857 0 0 0 16.143 10Z" />
                        </svg>
                        <span class="ms-3 flex-1 whitespace-nowrap">Kategoriler</span>
                        <span
                            class="ms-3 inline-flex items-center justify-center rounded-full bg-gray-100 px-2 text-sm font-medium text-gray-800 dark:bg-gray-700 dark:text-gray-300">Pro</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('blueprints.index') }}"
                        class="group flex items-center rounded-lg p-2 text-gray-900 hover:bg-gray-100 dark:text-white dark:hover:bg-gray-700">
                        <svg class="h-5 w-5 flex-shrink-0 text-gray-500 transition duration-75 group-hover:text-gray-900 dark:text-gray-400 dark:group-hover:text-white"
                            aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor"
                            viewBox="0 0 18 20">
                            <path
                                d="M17 5.923A1 1 0 0 0 16 5h-3V4a4 4 0 1 0-8 0v1H2a1 1 0 0 0-1 .923L.086 17.846A2 2 0 0 0 2.08 20h13.84a2 2 0 0 0 1.994-2.153L17 5.923ZM7 9a1 1 0 0 1-2 0V7h2v2Zm0-5a2 2 0 1 1 4 0v1H7V4Zm6 5a1 1 0 1 1-2 0V7h2v2Z" />
                        </svg>
                        <span class="ms-3 flex-1 whitespace-nowrap">Alan Planları</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('posts.index') }}"
                        class="group flex items-center rounded-lg p-2 text-gray-900 hover:bg-gray-100 dark:text-white dark:hover:bg-gray-700">
                        <svg class="h-5 w-5 flex-shrink-0 text-gray-500 transition duration-75 group-hover:text-gray-900 dark:text-gray-400 dark:group-hover:text-white"
                            aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor"
                            viewBox="0 0 20 20">
                            <path
                                d="m17.418 3.623-.018-.008a6.713 6.713 0 0 0-2.4-.569V2h1a1 1 0 1 0 0-2h-2a1 1 0 0 0-1 1v2H9.89A6.977 6.977 0 0 1 12 8v5h-2V8A5 5 0 1 0 0 8v6a1 1 0 0 0 1 1h8v4a1 1 0 0 0 1 1h2a1 1 0 0 0 1-1v-4h6a1 1 0 0 0 1-1V8a5 5 0 0 0-2.582-4.377ZM6 12H4a1 1 0 0 1 0-2h2a1 1 0 0 1 0 2Z" />
                        </svg>
                        <span class="ms-3 flex-1 whitespace-nowrap">Gönderiler</span>
                        <span
                            class="ms-3 inline-flex h-3 w-3 items-center justify-center rounded-full bg-blue-100 p-3 text-sm font-medium text-blue-800 dark:bg-blue-900 dark:text-blue-300">3</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('fields.index') }}"
                        class="group flex items-center rounded-lg p-2 text-gray-900 hover:bg-gray-100 dark:text-white dark:hover:bg-gray-700">
                        <svg class="h-5 w-5 flex-shrink-0 text-gray-500 transition duration-75 group-hover:text-gray-900 dark:text-gray-400 dark:group-hover:text-white"
                            aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor"
                            viewBox="0 0 18 20">
                            <path
                                d="M17 5.923A1 1 0 0 0 16 5h-3V4a4 4 0 1 0-8 0v1H2a1 1 0 0 0-1 .923L.086 17.846A2 2 0 0 0 2.08 20h13.84a2 2 0 0 0 1.994-2.153L17 5.923ZM7 9a1 1 0 0 1-2 0V7h2v2Zm0-5a2 2 0 1 1 4 0v1H7V4Zm6 5a1 1 0 1 1-2 0V7h2v2Z" />
                        </svg>
                        <span class="ms-3 flex-1 whitespace-nowrap">Alanlar</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('users.index') }}"
                        class="group flex items-center rounded-lg p-2 text-sm text-gray-900 hover:bg-gray-100 dark:text-white dark:hover:bg-gray-700">
                        <svg class="h-5 w-5 flex-shrink-0 text-sm text-gray-500 transition duration-75 group-hover:text-gray-900 dark:text-gray-400 dark:group-hover:text-white"
                            aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor"
                            viewBox="0 0 20 18">
                            <path
                                d="M14 2a3.963 3.963 0 0 0-1.4.267 6.439 6.439 0 0 1-1.331 6.638A4 4 0 1 0 14 2Zm1 9h-1.264A6.957 6.957 0 0 1 15 15v2a2.97 2.97 0 0 1-.184 1H19a1 1 0 0 0 1-1v-1a5.006 5.006 0 0 0-5-5ZM6.5 9a4.5 4.5 0 1 0 0-9 4.5 4.5 0 0 0 0 9ZM8 10H5a5.006 5.006 0 0 0-5 5v2a1 1 0 0 0 1 1h11a1 1 0 0 0 1-1v-2a5.006 5.006 0 0 0-5-5Z" />
                        </svg>
                        <span class="ms-3 flex-1 whitespace-nowrap">Kullanıcılar</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('activities') }}"
                        class="group flex items-center rounded-lg p-2 text-sm text-gray-900 hover:bg-gray-100 dark:text-white dark:hover:bg-gray-700">
                        <div
                            class="flex h-5 w-5 flex-shrink-0 items-center text-xl text-gray-500 transition duration-75 group-hover:text-gray-900 dark:text-gray-400 dark:group-hover:text-white">
                            <i class="fa fa-users-viewfinder"></i>
                        </div>
                        <span class="ms-3 flex-1 whitespace-nowrap">Aksiyonlar</span>
                    </a>
                </li>
            </ul>
            <ul class="flex-1 overflow-y-scroll">
                <li>
                    <ul class="hide-scrollbar max-h-72 space-y-2 overflow-y-scroll font-medium">
                        @foreach (modelGetAll('category')->where('active', 1) as $category)
                            <li class="group flex items-center gap-x-0.5">
                                <button
                                    edit-icon-trigger
                                    data-icon="{{ $category->icon }}"
                                    data-unique="{{ $category->id }}"
                                    data-title="{{ $category->title }}"
                                    class="flex h-8 w-8 items-center justify-center rounded-md text-xl text-gray-500 transition duration-75 hover:bg-gray-100 group-hover:text-gray-900 dark:text-gray-400 dark:hover:bg-gray-700 dark:group-hover:text-white">
                                    <i class="{{ $category->icon }}"></i>
                                </button>
                                <a href="{{ route('categories.show', $category->id) }}"
                                    class="flex-1 rounded-md p-2 px-4 text-left text-gray-900 hover:bg-gray-100 dark:text-white dark:hover:bg-gray-700">
                                    <span>{{ $category->title }}</span>
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </li>
            </ul>
            <ul class="mt-4 space-y-2 font-medium">
                <li>
                    <a href="{{ route('users.show', auth()->id()) }}"
                        class="group flex items-center rounded-lg p-2 text-gray-900 hover:bg-gray-100 dark:text-white dark:hover:bg-gray-700">
                        <div
                            class="flex h-5 w-5 flex-shrink-0 items-center text-xl text-gray-500 transition duration-75 group-hover:text-gray-900 dark:text-gray-400 dark:group-hover:text-white">
                            <i class="fa fa-user"></i>
                        </div>
                        <span class="ms-3">Profil</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('trash') }}"
                        class="group flex items-center rounded-lg p-2 text-gray-900 hover:bg-gray-100 dark:text-white dark:hover:bg-gray-700">
                        <div
                            class="flex h-5 w-5 flex-shrink-0 items-center text-xl text-gray-500 transition duration-75 group-hover:text-gray-900 dark:text-gray-400 dark:group-hover:text-white">
                            <i class="fa fa-trash"></i>
                        </div>
                        <span class="ms-3">Çöp Kutusu</span>
                    </a>
                </li>
                <li>
                    <form action="{{ route('logout') }}" class="w-full">
                        <button
                            class="group flex w-full items-center rounded-lg p-2 text-gray-900 hover:bg-gray-100 dark:text-white dark:hover:bg-gray-700">
                            <div
                                class="flex h-5 w-5 flex-shrink-0 items-center text-xl text-gray-500 transition duration-75 group-hover:text-gray-900 dark:text-gray-400 dark:group-hover:text-white">
                                <i class="fa fa-right-to-bracket"></i>
                            </div>
                            <span class="ms-3">Çıkış Yap</span>
                        </button>
                    </form>
                </li>
            </ul>
        </nav>
    </div>
</aside>