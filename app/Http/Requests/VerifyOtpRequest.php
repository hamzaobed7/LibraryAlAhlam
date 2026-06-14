<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;
use Override;

class VerifyOtpRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'email'    => 'required|email',
            'otp_code' => 'required|digits:6',
            'password' => ['required', 'string', 'min:6'],
            'name'     => 'required|min:3|max:30',
            'gender'   => 'required|in:Male,Female',
            'DOB'      => 'required|date',
            'cover'    => 'required|image|max:2000', 
            'phone'    => 'required|digits:10',
            'lang'     => 'required',
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
            'email.required'    => 'الايميل مطلوب لا يمكن تركه فارغا',
            'name.required'     => 'حقل الاسم مطلوب ولا يمكن تركه فارغاً.',
            'name.min'          => 'يجب ألا يقل الاسم عن 3 أحرف.',
            'name.max'          => 'يجب ألا يتجاوز الاسم 30 حرفاً.',
            'gender.required'   => 'يرجى تحديد الجنس.',
            'gender.in'         => 'القيمة المختارة للجنس غير صالحة.',
            'DOB.required'      => 'تاريخ الميلاد مطلوب.',
            'DOB.date'          => 'صيغة تاريخ الميلاد غير صحيحة.',
            'phone.required'    => 'رقم الهاتف مطلوب.',
            'phone.digits'      => 'يجب أن يتكون رقم الهاتف من 10 أرقام تماماً.',
            'lang.required'     => 'تحديد اللغة مطلوب.',
            'password.required' => 'كلمة المرور مطلوبة.',
            'password.min'      => 'يجب أن تكون كلمة المرور 6 أحرف على الأقل.',
            'cover.required'    => 'الصورة الشخصية مطلوبة.',
            'cover.image'       => 'الملف المرفوع يجب أن يكون صورة.',
            'cover.max'         => 'حجم الصورة يجب ألا يتجاوز 2 ميجابايت.',
        ];
    }
}