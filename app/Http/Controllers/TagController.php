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
        $tag = Tag::where('name', $name)->first();

        $searchData = $this->searchItems($request);

        return view('tags.show', compact(
            'tag',
            'searchData',
        ));
    }
}
