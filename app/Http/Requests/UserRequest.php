<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Override;

class UserRequest extends FormRequest
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
        $uesr=Auth::user();
        return [
           'name'=>['required','max:30','min:3'],
           'email'=> ['required',Rule::unique('users')->ignore($this->user())],
        ];
    }

    #[Override]
    public function failedValidation(Validator $validator)
    {
          $response = apiFail($validator->errors()->first(), 422);
        throw new HttpResponseException($response);
    }

    public function massage(){
        return[
            "name.required"=>"الاسم مطلوب",
            'email.required'=>"الحساب مطلوب"
        ];
    }
}
