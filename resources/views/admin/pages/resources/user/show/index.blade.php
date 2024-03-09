@extends('templates.admin')

@section('content')
    <section class="px-24">
        <div class="flex items-stretch gap-x-20">
            <div class="h-44  rounded-full"
                style="background-image: url({{ $user->avatar_source }}); background-position: center; background-size: cover; background-repeat: no-repeat; aspect-ratio: 1">
            </div>
            <div class="grid grid-rows-2">
                <div class="flex h-fit items-center gap-x-2">
                    <div class="mr-2 text-[1.7rem] font-normal">
                        <h1>{{ $user->name }}</h1>
                    </div>
                    <div class="grid grid-cols-2 place-items-start gap-x-1">
                        <a href="edit" class="btn-secondary w-full">
                            Edit profile
                        </a>
                        <a href="edit" class="btn-secondary w-full">
                            View actions
                        </a>
                    </div>
                </div>
                <ul class="flex gap-x-4 mt-3 mb-2">
                    <li class="text-lg">
                        <a href="user_posts">
                            <span class="font-semibold">
                                {{ getRelationsCount($user, 'post', 'user_id') }}
                            </span> Posts
                        </a>
                    </li>
                    <li class="text-lg">
                        <a href="user_categories">
                            <span class="font-semibold">
                                {{ getRelationsCount($user, 'category', 'user_id') }}
                            </span> Categories
                        </a>
                    </li>
                    <li class="text-lg">
                        <a href="user_blueprints">
                            <span class="font-semibold">
                                {{ getRelationsCount($user, 'blueprint', 'user_id') }}
                            </span> Blueprints
                        </a>
                    </li>
                    <li class="text-lg">
                        <a href="user_fields">
                            <span class="font-semibold">
                                {{ getRelationsCount($user, 'field', 'user_id') }}
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
                        <p class="leading-[1.3] text-sm font-medium">
                            {{ shortenText($user->biography) }}
                        </p>
                    </div>
                </div>
            </div>

        </div>
    </section>
@endsection
