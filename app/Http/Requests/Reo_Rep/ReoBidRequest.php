<?php

namespace App\Http\Requests\Reo_Rep;

use Illuminate\Foundation\Http\FormRequest;

class ReoBidRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            //
            'token' => 'required',
            'price'=>'required',
            'order_id'=>'required',
            'save_details'=>'required'
        ];
    }
}
