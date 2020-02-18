<?php

namespace App\Http\Requests\Users;

class UpdateUserRequest extends StoreUserRequest
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
            'phone' => 'required|digits:12|unique:users,phone,' . request()->route('user'),
            'email' => 'required|email|string|max:255|unique:users,email,' . request()->route('user')
        ]);
    }
}
