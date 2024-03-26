<select default name="{{ $field->handler }}" id="{{ $field->handler }}">
    @foreach ($field->onlyOptionFields() as $option)
        <option value="{{ $option->value.'__'.$option->id }}">{{ $option->label }}</option>
    @endforeach
</select>

{{-- this is good if you want to keep everyrhing in the field model --}}
{{-- but the catchy part that if we use field model as an select-option --}}
{{-- we are just using field's value and label row, and thats it --}}
{{-- so we are simply overloading field model just for these 2 features --}}