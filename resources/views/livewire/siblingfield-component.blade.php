<div>
    <div class="grid grid-cols-11 gap-3">
        @foreach ($fields as $idx => $field)
            <div class="col-span-{{ $col_span }} relative">
                <input class="flex-1" default name="{{ $field['handler'] }}" id="{{ $field['handler'] }}"
                    value="{{ $field['value'] ?? '' }}" type="{{ $field['type'] ?? 'text' }}" />
                @if ($idx % 2 != 0)
                    <div class="absolute -right-16 top-1/2 col-span-1 flex -translate-y-1/2 items-center">
                        <button type="button" wire:click="removeField({{ $idx }})" @disabled($idx <= 2)
                            @class(['btn-secondary', 'disabled' => $idx <= 2])>
                            Sil
                        </button>
                    </div>
                @endif
            </div>
        @endforeach
    </div>
    <div class="ml-auto mt-4 w-fit">
        <button type="button" class="btn-secondary" wire:click="addField">Alan Ekle</button>
    </div>
</div>
