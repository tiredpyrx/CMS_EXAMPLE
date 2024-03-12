@extends('templates.admin')

@section('content')
    <section class="px-24">
        <div class="flex items-stretch gap-x-20">
            <div class="h-44 rounded-full"
                style="background-image: url({{ $user->avatar }}); background-position: center; background-size: cover; background-repeat: no-repeat; aspect-ratio: 1">
            </div>
            <div class="grid grid-rows-1">
                <div class="flex h-fit items-center gap-x-2">
                    <div class="mr-2 text-[1.7rem] font-normal">
                        <h1>{{ $user->name }}</h1>
                    </div>
                    <div class="grid grid-cols-2 place-items-start gap-x-1">
                        <a href="{{ route('users.edit', $user->id) }}" class="btn-secondary w-full">
                            Profili Düzenle
                        </a>
                        <a href="{{ route('users.actions', $user->id) }}" class="btn-secondary w-full">
                            Aksiyonlar
                        </a>
                    </div>
                </div>
                <ul class="mb-2 mt-3 flex gap-x-4">
                    <li class="text-lg">
                        <a href="user_posts">
                            <span class="font-semibold">
                                {{ limitNumber(getRelationsCount($user, 'post', 'user_id'), 50) }}
                            </span> Posts
                        </a>
                    </li>
                    <li class="text-lg">
                        <a href="user_categories">
                            <span class="font-semibold">
                                {{ limitNumber(getRelationsCount($user, 'category', 'user_id'), 50) }}
                            </span> Categories
                        </a>
                    </li>
                    <li class="text-lg">
                        <a href="user_blueprints">
                            <span class="font-semibold">
                                {{ limitNumber(getRelationsCount($user, 'blueprint', 'user_id'), 50) }}
                            </span> Blueprints
                        </a>
                    </li>
                    <li class="text-lg">
                        <a href="user_fields">
                            <span class="font-semibold">
                                {{ limitNumber(getRelationsCount($user, 'field', 'user_id'), 50) }}
                            </span> Fields
                        </a>
                    </li>
                </ul>
                <div class="grid">
                    <div>
                        <h2 class="text-base font-semibold">
                            {{ $user->nickname }}
                        </h2>
                    </div>
                    <div>
                        <p class="text-sm font-medium leading-[1.3]">
                            {{ shortenText($user->biography) }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
