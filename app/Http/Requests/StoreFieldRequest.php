<?php

namespace App\Http\Requests;

use App\Enums\FieldTypes;
use App\Models\Field;
use App\Services\FieldService;

class StoreFieldRequest extends AppFormRequest
{
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
        return FieldService::getRequestRules($this);
    }
}
