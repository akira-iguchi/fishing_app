<?php
namespace App\Http\Composers;

use Illuminate\View\View;
use Illuminate\Http\Request;
use App\Models\Tag;
use App\Models\FishingType;
use App\Http\Requests\SearchSpotRequest;
use App\Consts\UserType;
use App\Consts\Status;
use App\Consts\ReadStatus;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Hash;
use App\Helpers\ClientReq;

class SpotSearchComposer
{
    public function compose(View $view, $req)
    {
        $allFishingTypeNames = FishingType::all();
        $searchWord = $req->input('searchWord');
        $fishingTypes = $req->input('fishing_types');
        $tags = Tag::all()->take(15);

        $view->with('allFishingTypeNames', $allFishingTypeNames);
        $view->with('searchWord', $searchWord);
        $view->with('fishingTypes', $fishingTypes);
        $view->with('tags', $tags);
    }
}
