<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\ValidationException;

class AppFormRequest extends FormRequest
{
    public function failedValidation(\Illuminate\Contracts\Validation\Validator $validator)
    {
        $response = redirect()
            ->back()
            ->with('error', $validator->errors()->first())
            ->withInput()
            ->withErrors($validator);

        throw (new ValidationException($validator, $response))
            ->errorBag($this->errorBag)
            ->redirectTo($this->getRedirectUrl());
    }
}
