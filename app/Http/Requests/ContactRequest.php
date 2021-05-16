<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ContactRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'email' => 'required|string|email',
            'title' => 'required|string|max:30',
            'body' => 'required|string|max:500',
        ];
    }

    public function attributes()
    {
        return [
            'email' => 'メールアドレス',
            'title' => 'タイトル',
            'body' => 'お問い合わせ内容',
        ];
    }
}
