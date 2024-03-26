<div class="flex">
    @if ($field->prefix)
        <span
            class="rounded-e-0 inline-flex items-center rounded-s-md border border-gray-300 bg-gray-200 px-3 text-sm text-gray-900 dark:border-gray-600 dark:bg-gray-600 dark:text-gray-400">
            {{ $field->prefix }}
        </span>
    @endif
    <textarea default data-max_value="{{ $field->max_value }}" style="max-height: 200px !important; resize: none"
        @class([
            '!rounded-l-none' => $field->prefix,
            '!rounded-r-none' => $field->suffix,
        ]) @required($field->required) min="{{ $field->min_value }}" max="{{ $field->max_value }}"
        name="{{ $field->handler }}" id="{{ $field->handler }}">{{ $field->value ?? old($field->handler) }}</textarea>
    @if ($field->suffix)
        <span
            class="rounded-s-0 inline-flex items-center rounded-e-md border border-gray-300 bg-gray-200 px-3 text-sm text-gray-900 dark:border-gray-600 dark:bg-gray-600 dark:text-gray-400">
            {{ $field->suffix }}
        </span>
    @endif
</div>
