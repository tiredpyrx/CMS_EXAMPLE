@extends('templates.admin')

@section('content')
    <ul>
        @foreach ($activities as $action)
            @if ($action->log_name === 'safe')
                <li class="mb-4 w-full">
                    <x-document-panel>
                        <div class="relative flex h-full items-baseline gap-x-2">
                            <ul class="mb-6">
                                <li>
                                    <div class="flex items-center gap-x-1">
                                        <div class="font-semibold">Konu:</div>
                                        <div>
                                            {{ $action['subject_class'] }}
                                        </div>
                                    </div>
                                </li>
                                <li>
                                    <div class="flex items-center gap-x-1">
                                        <div class="font-semibold">Birincil isim:</div>
                                        <div>
                                            @unless ($action['is_force_deleted'])
                                                <a @class([
                                                    'link-simple' => $action['is_deleted'],
                                                    'text-' . config('activitylog.EVENT_NAMES_CSS')[$action['event']],
                                                    'disabled' => $action['subject_model']->deleted_at,
                                                ])
                                                    href="{{ route("{$action['subject_route_prefix']}.show", $action['subject_id']) }}">
                                                    {{ $action['subject_model']->primary_text }}
                                                </a>
                                            @endunless
                                        </div>
                                    </div>
                                </li>
                                <li>
                                    <div class="flex items-center gap-x-1">
                                        <div class="font-semibold">Açıklama:</div>
                                        <div>
                                            {{ shortenText(shortenText($action['description'], 100), 200) }}
                                        </div>
                                    </div>
                                </li>
                                @if ($action['event'] == 'updated')
                                    <li>
                                        <div class="flex flex-col gap-x-1">
                                            <div class="font-semibold">Güncellenenler Eski:</div>
                                            <ul class="list-disc pl-10">
                                                @foreach ($action['properties']['old'] as $key => $val)
                                                    <li>
                                                        <div class="flex items-center gap-x-1">
                                                            <div class="font-semibold">
                                                                {{ ucfirst($key) }}:
                                                            </div>
                                                            <span>
                                                                {{ $val }}
                                                            </span>
                                                        </div>
                                                    </li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    </li>
                                    <li>
                                        <div class="flex flex-col gap-x-1">
                                            <div class="font-semibold">Güncellenenler Yeni:</div>
                                            <ul class="list-disc pl-10">
                                                @foreach ($action['properties']['attributes'] as $key => $val)
                                                    <li>
                                                        <div class="flex items-center gap-x-1">
                                                            <div class="font-semibold">
                                                                {{ ucfirst($key) }}:
                                                            </div>
                                                            <span>
                                                                {{ $val }}
                                                            </span>
                                                        </div>
                                                    </li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    </li>
                                @endif
                            </ul>
                            <div class="absolute right-1 top-1 text-sm font-light">
                                <i class="fa fa-calendar"></i>
                                {{ $action['updated_at']->diffForHumans() }}
                            </div>
                            @if ($action['event'] == 'updated' && !$action['is_deleted'] || !$action['is_force_deleted'])
                                <div class="absolute bottom-1 right-1">
                                    <button class="btn-secondary">Geri Al</button>
                                </div>
                            @endif
                        </div>
                    </x-document-panel>
                </li>
            @elseif ($action->log_name === 'private')
                <li>
                    <x-document-panel class="relative">
                        <ul class="grid">
                            <li>
                                <div class="flex items-center gap-x-1">
                                    <div class="font-semibold">Açıklama:</div>
                                    <div>
                                        {{ shortenText($action['description'], 100) }}
                                    </div>
                                </div>
                            </li>
                            <li>
                                <div class="flex items-center gap-x-1">
                                    <div class="font-semibold">User:</div>
                                    <div>
                                        <a class="link-simple"
                                            href="{{ route('users.show', $action['properties']['causer']['id']) }}">
                                            {{ $action['properties']['causer']['name'] }}
                                        </a>
                                    </div>
                                </div>
                            </li>
                            @foreach (collect($action['properties'])->except('causer')->toArray() as $key => $value)
                                <li>
                                    <div class="flex items-center gap-x-1">
                                        <div class="font-semibold">{{ $key }}:</div>
                                        <div>
                                            {{ $value ?: 'Unknown' }}
                                        </div>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                        <div class="absolute right-2 top-1 text-xs font-medium">
                            {{ $action['properties']['datetime'] }}&nbsp;|&nbsp;{{ dateHuman($action['properties']['datetime'], 'd/m/Y H:i:s') }}
                        </div>
                    </x-document-panel>
                </li>
            @elseif ($action->log_name === 'authentication_failed')
                <li>
                    <x-document-panel class="relative">
                        <ul class="grid">
                            <li>
                                <div class="flex items-center gap-x-1">
                                    <div class="font-semibold">Açıklama:</div>
                                    <div>
                                        {{ shortenText($action['description'], 100) }}
                                    </div>
                                </div>
                            </li>
                            @foreach ($action['properties'] as $key => $value)
                                <li>
                                    <div class="flex items-center gap-x-1">
                                        <div class="font-semibold">{{ $key }}:</div>
                                        <div>
                                            {{ $value ?: 'Unknown' }}
                                        </div>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                        <div class="absolute right-2 top-1 text-xs font-medium">
                            {{ $action['properties']['datetime'] }}
                        </div>
                    </x-document-panel>
                </li>
            @endif
        @endforeach
    </ul>
@endsection
