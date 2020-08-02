<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class VendorRequests extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
           'logo' => 'required_without:id|mimes:jpg,png,jpeg',
           'name' => 'required|string|max:150' ,
           'category_id' => 'required|exists:main_categories,id',
           'mobile' => 'required|max:100|unique:vendors,mobile,'.$this->id,
           'email' => 'required|max:100|email|unique:vendors,email,'.$this->id ,
           'password' => 'required_without:id',
           'address' => 'required|string|max:500' 
           

        ];
    }

    public function messages()
    {


        return [
            'required' =>'هذا الحقل مطلوب',
            'name.string' => 'الاسم لابد أن يكون أحرف',
            'name.max' => 'الاسم لابد ان لايذيد عن 150 حرف',
            'category_id.exists' =>'القسم غير موجود' ,
            'email.email' =>'بريد الكتروني غير صالح' ,
            'address.string'=>'العنوان لابد ان يكون حروف أو حروف و أرقام' ,
            'name.string'=>'الاسم لابد ان يكون حروف أو حروف و أرقام' ,
            'logo.required_without'=>'الصورة مطلوبة' ,
            'password.required' => 'كلمة المرور مطلوبة' ,
            'email' => 'البريد الاكتروني مطلوب'

            
        ];
    }
}
