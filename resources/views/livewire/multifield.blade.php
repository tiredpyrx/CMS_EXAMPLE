<div>
    <div class="grid grid-cols-12 gap-3">
        @foreach ($fields as $idx => $field)
            <div class="col-span-{{ $col_span }} relative">
                <input @required($field['required'] ?: false) default name="{{ $field['handler'] }}" id="{{ $field['handler'] }}"
                    value="{{ $field['value'] ?? '' }}" type="{{ $field['type'] ?? 'text' }}" />
                @unless ($loop->first)
                    <button type="button" wire:click="removeField({{ $idx }})" class="absolute right-4 top-1/2 h-6 w-6 -translate-y-1/2 rounded-full hover:bg-gray-200">
                        <i class="fa fa-xmark"></i>
                    </button>
                @endunless
            </div>
        @endforeach
    </div>
    <div class="ml-auto mt-4 w-fit">
        <button type="button" class="btn-secondary w-full" wire:click="addField">Alan Ekle</button>
    </div>
</div>
