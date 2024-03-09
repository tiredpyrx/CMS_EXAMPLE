<?php

namespace App\Http\Requests;

use App\Models\Field;
use Illuminate\Foundation\Http\FormRequest;

class UpdateFieldActiveRequest extends AppFormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'primaryValue' => function ($attribute, $value, $fail) {
                $field = Field::find($value);
                $fieldHandler = $field->getAttribute('handler');
                if ($fieldHandler == 'title' || $fieldHandler == 'slug')
                    $fail("{$field->label} alanının 'aktif' özelliği düzenlenemez!");
            }
        ];
    }
}
