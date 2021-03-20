<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EventRequest extends FormRequest
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
            'date' => 'required',
            'fishing_type' => 'required|max:20',
            'spot' => 'required|max:50',
            'bait' => 'nullable|max:20',
            'weather' => 'nullable|max:20',
            'fishing_start_time' => 'nullable',
            'fishing_end_time' => 'nullable',
            'detail' => 'nullable|max:100',
        ];
    }

    public function attributes()
    {
        return [
            'date' => '月日',
            'fishing_type' => '釣り方',
            'spot' => '釣り場',
            'bait' => 'エサ',
            'weather' => '天気',
            'fishing_start_time' => '開始時間',
            'fishing_end_time' => '終了時間',
            'detail' => '詳細',
        ];
    }
}
