<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rule;

class BookRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $book = $this->route('book'); 

        return [
            'ISBN'                => ['required', 'digits:13', Rule::unique('books', 'ISBN')->ignore($book?->id)],
            'title'               => 'required|string|max:150',
            'rental_price'        => 'nullable|numeric|min:0', 
            'deposit'             => 'nullable|numeric|min:0',
            'pages'               => 'nullable|integer|min:1',
            'default_borrow_days' => 'nullable|integer|min:1', 
            'total_copies'        => 'nullable|integer|min:0',
            'stock'               => 'nullable|integer|min:0',
            'published_at'        => 'nullable|date',
            'cover'               => 'nullable|image|max:2000',
            'category_id'         => 'required|exists:categories,id',
            'authors'             => 'required|array', 
            'authors.*'           => 'exists:authors,id' 
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        
        $response = apiFail($validator->errors()->first(), 422);
        throw new HttpResponseException($response);
    }
}