<?php

namespace App\Http\Requests\Orders;

use Illuminate\Foundation\Http\FormRequest;

class StoreOrderRequest extends FormRequest
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
            'client_id' => 'required|integer',
            'delivery' => 'nullable|boolean',
            'full_name' => 'required_if:delivery,1|string|max:255',
            'phone' => 'required_if:delivery,1|digits:12',
            'address' => 'required_if:delivery,1|string|max:255',
            'price' => 'required_if:delivery,1|numeric'
        ];
    }
}
