<!DOCTYPE html>
<html lang="en" class="hide-scrollbar">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Sibel Aksoy</title>
    @vite('resources/admin/css/app.css')
    @yield('css')
</head>

<body class="dark antialiased hide-scrollbar">

    @unless (Route::is('login'))
        <div class="flex max-h-screen">
            <x-sidebar />
            <div id="app_content" class="h-screen flex-1 overflow-y-scroll p-12 sm:ml-64 hide-scrollbar">
                @yield('content')
            </div>
        </div>
    @else
        @yield('content')
    @endunless

    @yield('js')

    <script src="https://kit.fontawesome.com/1e21adaaa9.js" crossorigin="anonymous"></script>
    @vite('resources/admin/js/app.js')
</body>

</html>
