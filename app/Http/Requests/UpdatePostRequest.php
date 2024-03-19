<?php

namespace App\Http\Requests;

use App\Models\Post;
use App\Services\PostService;

class UpdatePostRequest extends AppFormRequest
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
        $rules = Post::RULES;
        (new PostService)->handlePostsDetailedFieldsValidationOnUpdate($this, $rules);
        return $rules;
    }
}
