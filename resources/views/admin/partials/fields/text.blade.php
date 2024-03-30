<div class="flex">
    @if ($field->prefix)
        <span
            class="rounded-e-0 inline-flex items-center rounded-s-md border border-gray-300 bg-gray-200 px-3 text-sm text-gray-900 dark:border-gray-600 dark:bg-gray-600 dark:text-gray-400">
            {{ $field->prefix }}
        </span>
    @endif
    <input default data-max_value="{{ $field->max_value }}" @class([
        '!rounded-l-none' => $field->prefix,
        '!rounded-r-none' => $field->suffix,
    ]) @required($field->required) min="{{ $field->min_value }}"
        sluggable="{{ $field->sluggable }}"
        max="{{ $field->max_value }}" name="{{ $field->handler }}" id="{{ $field->handler }}" value="{{ $field->value ?? old($field->handler) }}"
        type="{{ $field->type }}" />
    @if ($field->suffix)
        <span
            class="rounded-s-0 inline-flex items-center rounded-e-md border border-gray-300 bg-gray-200 px-3 text-sm text-gray-900 dark:border-gray-600 dark:bg-gray-600 dark:text-gray-400">
            {{ $field->suffix }}
        </span>
    @endif
</div>
