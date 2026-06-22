<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\Auth;
use Override;

class Book_requestRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return (bool)Auth::user();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'book_title'=>['required','max:50','min:3'],
            'author_name'=>['required',"max:30","min:3"],
            ];
    }
    #[Override]
     protected function failedValidation(Validator $validator)
    {
        
        $response = apiFail($validator->errors()->first(), 422);
        throw new HttpResponseException($response);
    }

}
