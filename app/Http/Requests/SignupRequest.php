<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;
use Override;

class SignupRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'email' => 'required|email|unique:users,email',
        ];
    }

    #[Override]
    public function failedValidation(Validator $validator)
    {
        $response = apiFail($validator->errors()->first(), 422);
        throw new HttpResponseException($response);
    }
}