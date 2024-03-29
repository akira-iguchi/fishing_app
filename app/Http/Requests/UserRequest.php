<?php

namespace App\Http\Requests;

use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest
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
            'user_name' => 'required|string|max:10',
            'email' => 'required|string|email|max:255|unique:users,email,' . Auth::user()->email . ',email',
            'user_image' => 'nullable|image',
            'introduction' => 'nullable|string|max:100',
        ];
    }
}
