<?php

namespace App\Http\Controllers;

use App\Models\Tag;
use App\Models\Spot;
use App\Models\FishingType;
use Illuminate\Http\Request;

class TagController extends Controller
{
    public function show(string $name, Request $request)
    {
        $tag = Tag::where('name', $name)->first();
        $tags = Tag::all()->take(15);

        // 検索機能
        $allFishingTypeNames = FishingType::all();
        $searchWord = $request->input('searchWord');
        $fishingTypes = $request->input('fishing_types');

        return view('tags.show', [
            'tag' => $tag,
            'tags' => $tags,
            'allFishingTypeNames' => $allFishingTypeNames,
            'searchWord' => $searchWord,
            'fishingTypes' => $fishingTypes,
        ]);
    }
}
