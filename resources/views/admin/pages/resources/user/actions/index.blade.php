@extends('templates.admin')

@section('content')
    <main>
        <ul class="w-full">
            @foreach ($actions as $action)
                @include('admin.pages.resources.user.actions.partials.item')
            @endforeach
        </ul>
    </main>
@endsection
