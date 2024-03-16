<!DOCTYPE html>
<html lang="en" class="hide-scrollbar">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Sibel Aksoy</title>
    @routes
    @vite('resources/admin/css/output.css')
    @stack('css')
</head>

<body class="hide-scrollbar dark antialiased">

    @unless (Route::is('login'))
        <div class="flex max-h-screen">
            <x-sidebar />
            <div id="app_content" class="hide-scrollbar h-screen flex-1 overflow-y-scroll p-12 sm:ml-64">
                @yield('content')
            </div>
        </div>
    @else
        @yield('content')
    @endunless

    <div class="fixed left-0 top-0 z-50 hidden h-screen w-full place-items-center bg-black/25" id="app_icon_modal">
        <div class="relative h-1/3 w-1/3 rounded bg-gray-300 p-12">
            <button close type="button" class="absolute right-2 top-1 cursor-pointer p-3 text-lg">
                <i class="fa fa-xmark"></i>
            </button>
            <header class="mb-6">
                <h2 class="text-center text-2xl font-semibold"></h2>
            </header>
            <form action="" onsubmit="return false;" class="app-form">
                <div class="grid gap-6">
                    <input default />
                    <button class="btn-primary">Güncelle</button>
                </div>
            </form>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.3.0/datepicker.min.js"></script>
    @stack('js')
    <x-scripts />
    <x-session-alerts />
</body>

</html>
