<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class Remove_Frome_remainingRequest extends FormRequest
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
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
        'book_id'                => 'required|exists:books,id',
        'type'                   => 'required|string',
        'quantity'               => 'required|integer|min:1',
        'remove_from_remaining'  => 'required|boolean',
        ];
    }
}
