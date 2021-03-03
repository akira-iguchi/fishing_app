<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SpotCommentRequest extends FormRequest
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
            'comment' => 'required|max:150',
            'comment_image' => 'nullable|image'
        ];
    }

    public function attributes()
    {
        return [
            'comment' => 'コメント',
            'comment_image' => '画像'
        ];
    }
}
