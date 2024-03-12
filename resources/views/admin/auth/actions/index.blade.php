@extends('templates.admin')

@section('content')
    <main>
        <ul class="w-full">
            @foreach ($actions as $action)
                @include('admin.auth.actions.partials.item')
            @endforeach
        </ul>
    </main>
@endsection
