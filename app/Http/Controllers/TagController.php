<?php

namespace App\Http\Controllers;

use App\Models\Tag;
use App\Models\Spot;
use App\Models\FishingType;
use Illuminate\Http\Request;
use App\Http\Requests\SearchSpotRequest;

class TagController extends Controller
{
    public function searchItems($request)
    {
        $allFishingTypeNames = FishingType::all();
        $searchWord = $request->input('searchWord');
        $fishingTypes = $request->input('fishing_types');
        $tags = Tag::all()->take(15);

        return [$allFishingTypeNames, $searchWord, $fishingTypes, $tags];
    }

    public function __invoke(string $name, SearchSpotRequest $request)
    {
        $searchData = $this->searchItems($request);

        $tag = Tag::where('tag_name', $name)->first()->load('spots', 'spots.user');

        return [$searchData, $tag];
    }
}
