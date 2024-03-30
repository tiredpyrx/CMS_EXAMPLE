<div class="flex">
    <input value="{{ $field->value }}" step="{{ $field->step }}" type="range" type="text">
    <div class="inline-flex gap-x-0.5 text-sm font-semibold">
        <span class="range-display">{{ $field->value }}</span>
        <span class="tracking-tighter">/ {{ $field->max_value }}</span>
    </div>
</div>