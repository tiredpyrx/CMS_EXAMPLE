<?php

namespace App\Http\Requests;

use App\Models\Field;
use App\Models\Post;
use App\Services\PostService;

class StorePostRequest extends AppFormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $rules = Post::RULES;
        (new PostService)->handlePostsDetailedFieldsValidationOnStore($this, $rules);
        return $rules;
    }
}
