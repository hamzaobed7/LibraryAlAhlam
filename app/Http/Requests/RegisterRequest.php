<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class RegisterRequest extends FormRequest
{
    
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
            'email'     => ['required' , 'email' , Rule::unique('users')->whereNotNull('email_verified_at')],
            'password'  => 'required|min:6',            
            'name'      => 'required|string|max:100',
            
            'gender'    => 'nullable|in:Male,Female',
            'DOB'       => 'nullable|date',
            'phone'     => 'required|unique:customers',
            'avatar'    => 'nullable|image|max:2000',
            'lang'      => 'in:ar,en'
        ];
    }
}