<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LanguageRequest extends FormRequest
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
            'name'=>'required|string|max:100' ,
            'abbr'=>'required|string|max:100' ,
         //   'active'=>'required|in:1,0' ,
            'direction'=>'required|in:rtl,ltr' ,

        ];
    }

    public function messages()
    {


        return [
            'required' =>'هذا الحقل مطلوب',
            'name.string' => 'اسم اللغة لابد أن يكون أحرف',
            'name.max' => 'اسم اللغة لايذيد عن 100 حرف',
            'in' => ' قيمة خطأ',
            'abbr.max' => 'لا يذيد عن 10',
            'abbr.string' => 'نص فقط'
        ];
    }
}
