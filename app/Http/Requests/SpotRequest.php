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
            'spot_image_first' => 'nullable|image',
            'spot_image_second' => 'nullable|image',
            'spot_image_third' => 'nullable|image',
            'fishing_types' => 'nullable',
            // /^(?!.*\s).+$/uは、PHPにおいて半角スペースが無いこと、/^(?!.*\/).*$/uは/が無いことをチェックする正規表現
            'tags' => 'nullable|json|distinct|regex:/^(?!.*\s).+$/u|regex:/^(?!.*\/).*$/u',
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
            'spot_image_first' => '画像',
            'spot_image_second' => '画像',
            'spot_image_third' => '画像',
            'tags' => 'タグ',
            'fishing_types' => '釣り方',
        ];
    }

    public function passedValidation()
    {
        // collect関数を使ってコレクションに変換し、sliceメソッドやmapメソッドを使う
        $this->tags = collect(json_decode($this->tags))
            ->slice(0, 5)
            ->map(function ($requestTag) {
                return $requestTag->text;
            });
    }
}
