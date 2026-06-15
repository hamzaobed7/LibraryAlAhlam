<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Override;


class CustomerRequest extends FormRequest
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
        'email' => 'required|email|unique:users,email',
        'name'     => 'required|min:3|max:30',
        'gender'   => 'required|in:Male,Female',
        'DOB'      => 'required|date',
        'cover'    => 'required|image|max:2000',
        'phone'    => 'required|digits:10',
        'lang'     => 'required',
        'password' =>'required|min:6'
    ];
}

    #[Override]
    public function failedValidation(Validator $validator)
    {
        $response = apiFail($validator->errors()->first(), 422);
        throw new HttpResponseException($response);
    }

    #[Override]
    public function messages(): array
    {
        return [
           'email.required'=>'الايميل مطلوب لا يمكن تركه فارغا',
            'name.required'    => 'حقل الاسم مطلوب ولا يمكن تركه فارغاً.',
            'name.min'         => 'يجب ألا يقل الاسم عن 3 أحرف.',
            'name.max'         => 'يجب ألا يتجاوز الاسم 30 حرفاً.',
            'gender.required'  => 'يرجى تحديد الجنس.',
            'gender.in'        => 'القيمة المختارة للجنس غير صالحة (يجب أن تكون Male أو Female).',

        
            'DOB.required'     => 'تاريخ الميلاد مطلوب.',
            'DOB.date'         => 'صيغة تاريخ الميلاد غير صحيحة.',

           
            'phone.required'   => 'رقم الهاتف مطلوب.',
            'phone.digits'     => 'يجب أن يتكون رقم الهاتف من 10 أرقام تماماً.',

            
            'lang.required'    => 'تحديد اللغة مطلوب.',
             'password.required'=>"كلمة المرور مطلوبة",
             'password.min'   => 'اقل شي 6',  

            'email.unique' => 'Email must be unique',
            'user_id.required' => 'معرف المستخدم مطلوب.',
            'user_id.exists'   => 'المستخدم المحدد غير موجود في قاعدة البيانات.',
        ];
    }
}