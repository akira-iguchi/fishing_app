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
        $tags = Tag::take(15)->with([
                'spots',
                'spots.spotImages',
                'spots.spotComments',
                'spots.spotFavorites',
            ])->get();
        $searchWord = $request->searchWord;
        $fishingTypesId = $request->fishingTypes;

        return [$allFishingTypeNames, $tags, $searchWord, $fishingTypesId];
    }

    public function __invoke(String $name, SearchSpotRequest $request)
    {
        $searchData = $this->searchItems($request);

        $tag = Tag::where('tag_name', $name)->first()->load([
                'spots',
                'spots.spotImages',
                'spots.spotComments',
                'spots.spotFavorites',
            ]);

        $tagSpots = $tag->spots()
            ->with(['user', 'spotImages', 'spotFavorites', 'spotComments'])
            ->latest()->get();

        return [$searchData, $tag, $tagSpots];
    }
}
