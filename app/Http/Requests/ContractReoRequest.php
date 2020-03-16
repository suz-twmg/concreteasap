<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ContractReoRequest extends FormRequest
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
            'suburb' => 'required|max:255',
            'delivery_date' => 'required',
            "time_preference1" => 'required',
            "time_preference2" => 'required',
            "time_preference3" => 'required',
            "time_deliveries" => 'required',
            "urgency" => 'required',
            "preference" => 'required',
            "products" => 'required'
        ];
    }
}
