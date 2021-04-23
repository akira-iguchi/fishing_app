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
    public function searchItems($request)
    {
        $allFishingTypeNames = FishingType::all();
        $searchWord = $request->input('searchWord');
        $fishingTypes = $request->input('fishing_types');
        $tags = Tag::all()->take(15);

        return [$allFishingTypeNames, $searchWord, $fishingTypes, $tags];
    }

    public function index(SearchSpotRequest $request)
    {

        if (Auth::check()) {
            $searchData = $this->searchItems($request);

            // 最近の投稿
            $recentSpots = Spot::all()->sortByDesc('id')->take(12)
                        ->load(['user', 'spotImages', 'spotFavorites', 'spotComments']);

            // フォローしたユーザーの釣りスポット
            if (Auth::user()->count_followings > 0) {
                $followUserSpots = Spot::where('user_id', Auth::user()->followings()->pluck('followee_id'))
                                    ->with(['user', 'spotImages', 'spotFavorites', 'spotComments'])->take(8)
                                    ->get()->sortByDesc('id');
            } else {
                $followUserSpots = null;
            }

            // いいねランキング
            $rankSpots = Spot::withCount('spotFavorites')->orderBy('spot_favorites_count', 'desc')
                        ->with(['user', 'spotImages', 'spotFavorites', 'spotComments'])->take(4)->get();

            return [$searchData, $recentSpots, $followUserSpots, $rankSpots];
        } else {
            return response()->json();
        }
    }

    public function search(SearchSpotRequest $request)
    {
        $searchData = $this->searchItems($request);

        list($query, $searchFishingTypeName) = $request->filters($searchData[1], $searchData[2]);

        $spots = $query->get();

        $searchFishingTypes = $searchFishingTypeName;

        return view('spots.searches.search', compact(
            'searchData',
            'spots',
            'searchFishingTypes',
        ));
    }

    public function show(String $id)
    {
        $spot = Spot::findOrFail($id)
            ->load(
                ['user', 'spotImages', 'spotFavorites', 'tags', 'fishingTypes', 'spotComments', 'spotComments.user']
            );

        // その他の釣りスポット
        $otherSpots = Spot::where('id', '!=', $spot->id)->get()->shuffle()->take(4)
                    ->load(['user', 'spotImages', 'spotFavorites', 'spotComments']);

        return [$spot, $otherSpots] ?? abort(404);
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
        clock($request->fishing_types);
        if ($request->has('fishing_types')) {
            $fishingTypeId = explode(",", $request->fishing_types);
            $spot->fishingTypes()->attach($fishingTypeId);
        }

        return response($spot, 201);
    }

    public function edit(Spot $spot)
    {
        if (\Auth::id() === $spot->user_id) {
            $tagNames = $spot->tags->map(function ($tag) {
                return ['text' => $tag->tag_name];
            });

            $allTagNames = TagNameTrait::getAllTagNames();

            $allFishingTypeNames = FishingType::all();

            return view('spots.edit', [
                'spot' => $spot,
                'tagNames' => $tagNames,
                'allTagNames' => $allTagNames,
                'allFishingTypeNames' => $allFishingTypeNames,
            ]);
        } else {
            session()->flash('error_message', '自信が投稿した釣りスポットのみ編集できます');
            return redirect('/');
        }
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
            $spot->fishingTypes()->attach($request->fishing_types);

            session()->flash('flash_message', '釣りスポットを更新しました');
            return redirect()->route('spots.show', [$spot]);
        });
    }

    public function destroy(Spot $spot)
    {
        if (\Auth::id() === $spot->user_id) {
            $spot->delete();
            return redirect('/')->with('flash_message', '釣りスポットを削除しました');
        } else {
            return redirect('/')->with('flash_message', '自信の投稿のみ削除できます');
        }
    }
}
