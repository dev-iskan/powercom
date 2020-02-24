<?php

namespace App\Http\Requests\Clients;


class UpdateClientRequest extends StoreClientRequest
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
        return array_merge(parent::rules(), [
            'phone' => 'required|digits:12|unique:clients,phone,'.request()->route('client'),
            'email' => 'required|email|max:255|unique:clients,email,'.request()->route('client'),
        ]);
    }
}
