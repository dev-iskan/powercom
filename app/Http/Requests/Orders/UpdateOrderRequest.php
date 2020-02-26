<?php

namespace App\Http\Requests\Orders;

use Illuminate\Support\Arr;

class UpdateOrderRequest extends StoreOrderRequest
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
        return Arr::except(parent::rules(), ['client_id']);
    }
}
