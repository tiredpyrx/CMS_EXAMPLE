<?php

namespace App\Http\Requests;

use App\Models\Category;

class StoreCategoryRequest extends AppFormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return Category::RULES;
    }
}
