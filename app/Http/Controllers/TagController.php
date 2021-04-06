<?php

namespace App\Http\Controllers;

use App\Models\Tag;
use App\Models\Spot;
use App\Models\FishingType;
use Illuminate\Http\Request;

class TagController extends Controller
{
    public function __invoke(string $name, Request $request)
    {
        $tag = Tag::where('name', $name)->first();

        // 検索機能
        $tags = Tag::all()->take(15);
        $allFishingTypeNames = FishingType::all();
        $searchWord = $request->input('searchWord');
        $fishingTypes = $request->input('fishing_types');

        return view('tags.show', compact(
            'tag',
            'tags',
            'allFishingTypeNames',
            'searchWord',
            'fishingTypes',
        ));
    }
}
