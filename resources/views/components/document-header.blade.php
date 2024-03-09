<div class="p-8 shadow-md bg-white text-black rounded mb-6">
    <div class="flex justify-between">
        <h1 class="text-2xl font-extrabold">{!! $title !!}</h1>
        <div class="flex items-center gap-x-1 justify-end flex-1">
            {{ $slot }}
        </div>
    </div>
</div>