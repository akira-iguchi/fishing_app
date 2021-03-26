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
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class SpotController extends Controller
{
    public static function escapeLike($str)
    {
        return str_replace(['\\', '%', '_'], ['\\\\', '\%', '\_'], $str);
    }

    public function index(Request $request)
    {
        if (Auth::check()) {
            // 最近の投稿
            $recentSpots = Spot::all()->sortByDesc('id')->take(12)
                        ->load(['user', 'spot_images', 'spot_favorites', 'spot_comments']);

            $tags = Tag::all()->take(15);

            // フォローしたユーザーの釣りスポット
            if (Auth::user()->count_followings !== 0) {
                $followUserSpots = Spot::query()->where('user_id', Auth::user()->followings()->pluck('followee_id'))
                            ->with(['user', 'spot_images', 'spot_favorites', 'spot_comments'])->take(8)->get()->sortByDesc('id');
            } else {
                $followUserSpots = null;
            }

            // いいねランキング
            $rankSpots = Spot::withCount('spot_favorites')->orderBy('spot_favorites_count', 'desc')
                        ->with(['user', 'spot_images', 'spot_favorites', 'spot_comments'])->take(4)->get();

            // 検索機能
            $allFishingTypeNames = FishingType::all();
            $searchWord = $request->input('searchWord');
            $fishingTypes = $request->input('fishing_types');

            return view('spots.index', [
                'recentSpots' => $recentSpots,
                'tags' => $tags,
                'followUserSpots' => $followUserSpots,
                'rankSpots' => $rankSpots,
                'allFishingTypeNames' => $allFishingTypeNames,
                'searchWord' => $searchWord,
                'fishingTypes' => $fishingTypes,
            ]);
        } else {
            return view('spots.index');
        }
    }

    public function search(Request $request)
    {
        $allFishingTypeNames = FishingType::all();
        $searchWord = $request->input('searchWord');
        $fishingTypes = $request->input('fishing_types');
        $tags = Tag::all()->take(15);

        $query = Spot::query();

        if (isset($searchWord) && is_array($fishingTypes)) {
            // （釣りスポット名または所在地）かつ、釣りスポットにおすすめの釣り方を取得。釣りスポットは、釣り方を１つでも含んでいたら表示
            $query->where(function($query) use($searchWord) {
                $query->where('spot_name', 'like', '%' . self::escapeLike($searchWord) . '%')
                ->orWhere('address', 'like', '%' . self::escapeLike($searchWord) . '%');
            })
            ->whereHas('fishing_types', function($query) use($fishingTypes){
                $query->where('fishing_type_id', $fishingTypes);
            });

            $searchFishingTypes = FishingType::whereIn('id', $fishingTypes)->pluck('fishing_type_name');
        } else {
            if (is_array($fishingTypes)) {
                $query->whereHas('fishing_types', function($query) use($fishingTypes){
                    $query->where('fishing_type_id', $fishingTypes);
                });

                $searchFishingTypes = FishingType::whereIn('id', $fishingTypes)->pluck('fishing_type_name');
            } else {
                $searchFishingTypes = null;
            }

            if (isset($searchWord)) {
                $query->where(function($query) use($searchWord) {
                    $query->where('spot_name', 'like', '%' . self::escapeLike($searchWord) . '%')
                    ->orWhere('address', 'like', '%' . self::escapeLike($searchWord) . '%');
                });
            }
        }

        $spots = $query->get();

        return view('spots.searches.search', [
            'spots' => $spots,
            'tags' => $tags,
            'allFishingTypeNames' => $allFishingTypeNames,
            'searchWord' => $searchWord,
            'fishingTypes' => $fishingTypes,
            'searchFishingTypes' => $searchFishingTypes,
        ]);
    }

    public function show(Spot $spot)
    {
        // その他の釣りスポット
        $otherSpots = Spot::where('id','!=', $spot->id)->get()->shuffle()
                ->take(4)
                ->load(['user', 'spot_images', 'spot_favorites', 'spot_comments', 'fishing_types']);

        return view('spots.show', [
            'spot' => $spot,
            'otherSpots' => $otherSpots,
        ]);
    }

    public function create(Spot $spot)
    {
        $allTagNames = TagNameTrait::getAllTagNames();

        $allFishingTypeNames = FishingType::all();

        return view('spots.create', [
            'spot' => $spot,
            'allTagNames' => $allTagNames,
            'allFishingTypeNames' => $allFishingTypeNames,
        ]);
    }

    public function store(SpotRequest $request, Spot $spot, SpotImage $spot_image)
    {
        $spot->fill($request->all());
        $spot->user_id = auth()->id();
        $spot->save();

        $image1 = 'spot_image1';
        $image2 = 'spot_image2';
        $image3 = 'spot_image3';

        // 画像（小テーブル）とリレーション
        if ($request->hasFile('spot_image1') || $request->hasFile('spot_image2') || $request->hasFile('spot_image3')) {
            $spot_image->spot_id = $spot->id;
            if ($request->hasFile('spot_image1') && $request->hasFile('spot_image2') && $request->hasFile('spot_image3')) {
                SpotTrait::imageUpload($spot, $request, $image1);
                SpotTrait::imageUpload($spot, $request, $image2);
                SpotTrait::imageUpload($spot, $request, $image3);
            } elseif ($request->hasFile('spot_image1') && $request->hasFile('spot_image2')) {
                SpotTrait::imageUpload($spot, $request, $image1);
                SpotTrait::imageUpload($spot, $request, $image2);
            } elseif ($request->hasFile('spot_image2') && $request->hasFile('spot_image3')) {
                SpotTrait::imageUpload($spot, $request, $image2);
                SpotTrait::imageUpload($spot, $request, $image3);
            } elseif ($request->hasFile('spot_image1') && $request->hasFile('spot_image3')) {
                SpotTrait::imageUpload($spot, $request, $image1);
                SpotTrait::imageUpload($spot, $request, $image3);
            } elseif ($request->hasFile('spot_image1')) {
                SpotTrait::imageUpload($spot, $request, $image1);
            } elseif ($request->hasFile('spot_image2')) {
                SpotTrait::imageUpload($spot, $request, $image2);
            } else {
                SpotTrait::imageUpload($spot, $request, $image3);
            }
        } else {
            $spot_image->spot_id = $spot->id;
            $spot_image->save();
        }

        // タグとリレーション
        $request->tags->each(function ($tagName) use ($spot) {
            $tag = Tag::firstOrCreate(['name' => $tagName]);
            $spot->tags()->attach($tag);
        });

        // 釣り方とリレーション
        $spot->fishing_types()->attach($request->fishing_types);

        return redirect('/')->with('flash_message', '釣りスポットを投稿しました');
    }

    public function edit(Spot $spot)
    {
        if (\Auth::id() === $spot->user_id) {
            $tagNames = $spot->tags->map(function ($tag) {
                return ['text' => $tag->name];
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
        $spot->fill($request->all());
        $spot->user_id = auth()->id();
        $spot->save();

        $image1 = 'spot_image1';
        $image2 = 'spot_image2';
        $image3 = 'spot_image3';

        // 画像（小テーブル）とリレーション
        if ($request->hasFile('spot_image1') || $request->hasFile('spot_image2') || $request->hasFile('spot_image3')) {
            $spot->spot_images()->delete();
            $spot_image->spot_id = $spot->id;
            if ($request->hasFile('spot_image1') && $request->hasFile('spot_image2') && $request->hasFile('spot_image3')) {
                SpotTrait::imageUpload($spot, $request, $image1);
                SpotTrait::imageUpload($spot, $request, $image2);
                SpotTrait::imageUpload($spot, $request, $image3);
            } elseif ($request->hasFile('spot_image1') && $request->hasFile('spot_image2')) {
                SpotTrait::imageUpload($spot, $request, $image1);
                SpotTrait::imageUpload($spot, $request, $image2);
            } elseif ($request->hasFile('spot_image2') && $request->hasFile('spot_image3')) {
                SpotTrait::imageUpload($spot, $request, $image2);
                SpotTrait::imageUpload($spot, $request, $image3);
            } elseif ($request->hasFile('spot_image1') && $request->hasFile('spot_image3')) {
                SpotTrait::imageUpload($spot, $request, $image1);
                SpotTrait::imageUpload($spot, $request, $image3);
            } elseif ($request->hasFile('spot_image1')) {
                SpotTrait::imageUpload($spot, $request, $image1);
            } elseif ($request->hasFile('spot_image2')) {
                SpotTrait::imageUpload($spot, $request, $image2);
            } elseif ($request->hasFile('spot_image3')) {
                SpotTrait::imageUpload($spot, $request, $image3);
            } else {
                $spot_image->save();
            }
        }

        $spot->tags()->detach();
        $request->tags->each(function ($tagName) use ($spot) {
            $tag = Tag::firstOrCreate(['name' => $tagName]);
            $spot->tags()->attach($tag);
        });

        $spot->fishing_types()->detach($request->fishing_types);
        $spot->fishing_types()->attach($request->fishing_types);

        session()->flash('flash_message', '釣りスポットを更新しました');
        return redirect()->route('spots.show', [$spot]);
    }

    public function destroy(Spot $spot)
    {
        if (\Auth::id() === $spot->user_id) {
            $spot->delete();
            return redirect('/')->with('flash_message', '釣りスポットを削除しました');
        } else {
            return redirect('/')->with('flash_message', '釣りスポットを削除できませんでした');
        }
    }

    public function favorite(Request $request, Spot $spot)
    {
        $spot->spot_favorites()->detach($request->user()->id);
        $spot->spot_favorites()->attach($request->user()->id);

        return [
            'spot' => $spot,
            'countSpotFavorites' => $spot->count_spot_favorites,
        ];
    }

    public function unfavorite(Request $request, Spot $spot)
    {
        $spot->spot_favorites()->detach($request->user()->id);

        return [
            'spot' => $spot,
            'countSpotFavorites' => $spot->count_spot_favorites,
        ];
    }
}
