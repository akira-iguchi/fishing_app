<?php

namespace App\Http\Controllers;

use App\Models\Spot;
use App\Models\Tag;
use App\Models\User;
use App\Models\SpotImage;
use App\Models\SpotComment;
use App\Models\FishingType;
use Illuminate\Http\Request;
use App\Traits\SpotTrait;
use App\Traits\TagNameTrait;
use App\Http\Requests\SpotRequest;
use App\Http\Requests\SearchSpotRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class SpotController extends Controller
{
    use TagNameTrait;

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

    public function index(SearchSpotRequest $request)
    {
        if (Auth::check()) {
            $searchData = $this->searchItems($request);

            // 最近の投稿
            $recentSpots = Spot::take(8)->with([
                    'user',
                    'spotImages',
                    'spotFavorites',
                    'spotComments'
                ])
                ->latest()->get();

            // フォローしたユーザーの投稿
            if (Auth::user()->count_followings > 0) {
                $followUserSpots = Spot::query()
                ->whereIn('user_id', Auth::user()->followings()->pluck('followee_id'))
                ->with(['user', 'spotImages', 'spotFavorites', 'spotComments'])
                ->take(8)->latest()->get();
            } else {
                $followUserSpots = null;
            }

            // いいねランキング
            $rankingSpots = Spot::withCount('spotFavorites')->orderBy('spot_favorites_count', 'desc')
            ->with(['user', 'spotImages', 'spotFavorites', 'spotComments'])->take(6)->get();

            return [$searchData, $recentSpots, $followUserSpots, $rankingSpots];
        } else {
            return response()->json();
        }
    }

    public function search(SearchSpotRequest $request)
    {
        $searchData = $this->searchItems($request);

        list($query, $searchFishingTypeName) = $request->filters($searchData[2], $searchData[3]);

        $spots = $query->with(['user', 'spotImages', 'spotFavorites', 'spotComments'])
        ->latest()->paginate();

        return [$searchData, $spots, $searchFishingTypeName];
    }

    public function show(String $id)
    {
        $spot = Spot::findOrFail($id)
            ->load(
                [
                    'user.followings',
                    'user.followers',
                    'spotImages',
                    'spotFavorites',
                    'tags',
                    'fishingTypes',
                    'spotComments.user'
                ]
            );

        // その他の釣りスポット
        $otherSpots = Spot::where('id', '!=', $spot->id)->get()->shuffle()->take(4)
                    ->load(['user', 'spotImages', 'spotFavorites', 'spotComments']);

        return [$spot, $otherSpots];
    }

    public function create(Spot $spot)
    {
        $allTagNames = TagNameTrait::getAllTagNames();

        $allFishingTypeNames = FishingType::all();

        return [$allTagNames, $allFishingTypeNames];
    }

    public function store(SpotRequest $request, Spot $spot, SpotImage $spot_image)
    {
        $spot->user_id = auth()->id();
        $spot->fill($request->all())->save();

        SpotTrait::imageUploadByCase($spot, $request, $spot_image);

        // タグとリレーション
        $request->tags->each(function ($tagName) use ($spot) {
            $tag = Tag::firstOrCreate(['tag_name' => $tagName]);
            $spot->tags()->attach($tag);
        });

        // 釣り方とリレーション
        if ($request->filled('fishing_types')) {
            // 配列化
            $fishingTypeId = explode(",", $request->fishing_types);
            $spot->fishingTypes()->attach($fishingTypeId);
        }

        return response($spot, 201);
    }

    public function edit(String $id)
    {
        $spot = Spot::findOrFail($id)->load(['fishingTypes']);

        $spotFishingTypeNames = $spot->fishingTypes->pluck('id');

        $spotTagNames = $spot->tags->map(function ($tag) {
            return ['text' => $tag->tag_name];
        });

        $allTagNames = TagNameTrait::getAllTagNames();

        $allFishingTypeNames = FishingType::all();

        return [$spot, $spotFishingTypeNames, $spotTagNames, $allTagNames, $allFishingTypeNames];
    }

    public function update(SpotRequest $request, Spot $spot, SpotImage $spot_image)
    {
        return DB::transaction(function () use ($spot, $spot_image, $request) {
            $spot->user_id = auth()->id();
            $spot->fill($request->all())->save();

            SpotTrait::imageUploadByCase($spot, $request, $spot_image);

            $spot->tags()->detach();
            $request->tags->each(function ($tagName) use ($spot) {
                $tag = Tag::firstOrCreate(['tag_name' => $tagName]);
                $spot->tags()->attach($tag);
            });

            $spot->fishingTypes()->detach();
            if ($request->filled('fishing_types')) {
                // 配列化
                $fishingTypeId = explode(",", $request->fishing_types);
                $spot->fishingTypes()->attach($fishingTypeId);
            }

            return response($spot, 201);
        });
    }

    public function destroy(Spot $spot)
    {
        if (\Auth::id() === $spot->user_id) {
            $spot->delete();
            return response()->json();
        } else {
            return response()->json();
        }
    }
}
