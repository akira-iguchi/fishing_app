<?php

namespace App\Http\Requests;

use App\Models\Spot;
use App\Models\FishingType;
use Illuminate\Foundation\Http\FormRequest;

class SearchSpotRequest extends FormRequest
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
            'searchWord' => 'nullable',
            'fishing_types' => 'nullable'
        ];
    }

    public function escapeLike($str)
    {
        return str_replace(['\\', '%', '_'], ['\\\\', '\%', '\_'], $str);
    }

    public function filters($searchWord, $fishingTypes)
    {
        $query = Spot::query();

        if (isset($searchWord) && is_array($fishingTypes)) {
            // （釣りスポット名または所在地）かつ、その釣りスポットにおすすめの釣り方を取得。釣りスポットは、釣り方を１つでも含んでいたら表示
            $query->where(function ($query) use ($searchWord, $fishingTypes) {
                $query->where('spot_name', 'like', '%' . self::escapeLike($searchWord) . '%')
                ->orWhere('address', 'like', '%' . self::escapeLike($searchWord) . '%');
            })
            ->whereHas('fishingTypes', function ($query) use ($fishingTypes) {
                $query->where('fishing_type_id', $fishingTypes);
            });

            $searchFishingTypes = FishingType::whereIn('id', $fishingTypes)->pluck('fishing_type_name');
        } else {
            if (is_array($fishingTypes)) {
                $query->whereHas('fishingTypes', function ($query) use ($fishingTypes) {
                    $query->where('fishing_type_id', $fishingTypes);
                });

                $searchFishingTypes = FishingType::whereIn('id', $fishingTypes)->pluck('fishing_type_name');
            } else {
                $searchFishingTypes = null;
            }

            if (isset($searchWord)) {
                $query->where(function ($query) use ($searchWord) {
                    $query->where('spot_name', 'like', '%' . self::escapeLike($searchWord) . '%')
                    ->orWhere('address', 'like', '%' . self::escapeLike($searchWord) . '%');
                });
            }
        }

        return $query;
    }

    public function filtersName($searchWord, $fishingTypes)
    {
        $query = Spot::query();

        if (isset($searchWord) && is_array($fishingTypes)) {
            // （釣りスポット名または所在地）かつ、その釣りスポットにおすすめの釣り方を取得。釣りスポットは、釣り方を１つでも含んでいたら表示
            $query->where(function ($query) use ($searchWord, $fishingTypes) {
                $query->where('spot_name', 'like', '%' . self::escapeLike($searchWord) . '%')
                ->orWhere('address', 'like', '%' . self::escapeLike($searchWord) . '%');
            })
            ->whereHas('fishingTypes', function ($query) use ($fishingTypes) {
                $query->where('fishing_type_id', $fishingTypes);
            });

            $searchFishingTypes = FishingType::whereIn('id', $fishingTypes)->pluck('fishing_type_name');
        } else {
            if (is_array($fishingTypes)) {
                $query->whereHas('fishingTypes', function ($query) use ($fishingTypes) {
                    $query->where('fishing_type_id', $fishingTypes);
                });

                $searchFishingTypes = FishingType::whereIn('id', $fishingTypes)->pluck('fishing_type_name');
            } else {
                $searchFishingTypes = null;
            }

            if (isset($searchWord)) {
                $query->where(function ($query) use ($searchWord) {
                    $query->where('spot_name', 'like', '%' . self::escapeLike($searchWord) . '%')
                    ->orWhere('address', 'like', '%' . self::escapeLike($searchWord) . '%');
                });
            }
        }

        return $searchFishingTypes;
    }
}
