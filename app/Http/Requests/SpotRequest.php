<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SpotRequest extends FormRequest
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
            'spot_name' => 'required|max:20',
            'explanation' => 'required|max:300',
            'address' => 'max:50',
            'latitude' => 'required',
            'longitude' => 'required',
            'spot_image1' => 'nullable|image|mimes:jpeg,png,jpg',
            'spot_image2' => 'nullable|image|mimes:jpeg,png,jpg',
            'spot_image3' => 'nullable|image|mimes:jpeg,png,jpg',
            'tags' => 'json|distinct|regex:/^(?!.*\s).+$/u|regex:/^(?!.*\/).*$/u',
        ];
    }

    public function attributes()
    {
        return [
            'spot_name' => '釣りスポット名',
            'explanation' => '説明',
            'address' => '所在地',
            'latitude' => '緯度',
            'longitude' => '経度',
            'spot_image1' => '画像',
            'spot_image2' => '画像',
            'spot_image3' => '画像',
            'tags' => 'タグ',
            'fishing_types' => '釣り方',
        ];
    }

    public function passedValidation()
    {
        $this->tags = collect(json_decode($this->tags))
            ->slice(0, 5)
            ->map(function ($requestTag) {
                return $requestTag->text;
            });

        $this->fishing_types = collect($this->fishing_types)
            ->slice(0, 8);
    }
}
