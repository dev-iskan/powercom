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
            'full_name' => 'required_with:delivery|string|max:255',
            'phone' => 'required_with:delivery|digits:12',
            'address' => 'required_with:delivery|string|max:255',
            'price' => 'required_with:delivery|numeric'
        ];
    }
}
