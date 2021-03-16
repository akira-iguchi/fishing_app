<?php

namespace App\Http\Controllers;

use App\Models\Spot;
use App\Models\Tag;
use App\Models\FishingType;
use App\Models\SpotComment;
use Illuminate\Http\Request;
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
            $recentSpots = Spot::all()->sortByDesc('id')
                        ->load(['user', 'spot_favorites', 'spot_comments']);

            $tags = Tag::all()->take(15);

            // いいねランキング
            $rankSpots = Spot::withCount('spot_favorites')->orderBy('spot_favorites_count', 'desc')
                        ->with(['user', 'spot_favorites', 'spot_comments'])->take(4)->get();

            // 検索機能
            $allFishingTypeNames = FishingType::all();
            $searchWord = $request->input('searchWord');
            $fishingTypes = $request->input('fishing_types');

            return view('spots.index', [
                'recentSpots' => $recentSpots,
                'tags' => $tags,
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
            // （釣り場名または所在地）かつ、釣り場におすすめの釣り方を取得。釣り場は、釣り方を１つでも含んでいたら表示
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
                ->load(['user', 'spot_favorites', 'spot_comments', 'fishing_types']);

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

    public function store(SpotRequest $request, Spot $spot)
    {
        $spot->fill($request->except('spot_image'));
        if ($request->hasFile('spot_image')) {
            Storage::delete('public' . $spot->spot_image);
            $filePath = $request->file('spot_image')->store('public');
            $spot->spot_image = basename($filePath);
            // $upload_info = Storage::disk('s3')->putFile('/test', $request->file('spot_image'), 'public');

            //S3へのファイルアップロード処理の時の情報が格納された変数$upload_infoを用いてアップロードされた画像へのリンクURLを変数$pathに格納する
            // $path = Storage::disk('s3')->url($upload_info);
            // $spot->spot_image = $path;
        }
        $spot->user_id = auth()->id();
        $spot->save();

        $request->tags->each(function ($tagName) use ($spot) {
            $tag = Tag::firstOrCreate(['name' => $tagName]);
            $spot->tags()->attach($tag);
        });

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

    public function update(SpotRequest $request, Spot $spot)
    {
        $spot->fill($request->except('spot_image'));
        if ($request->hasFile('spot_image')) {
            Storage::delete('public' . $spot->spot_image);
            $filePath = $request->file('spot_image')->store('public');
            $spot->spot_image = basename($filePath);
            // $upload_info = Storage::disk('s3')->putFile('/test', $request->file('spot_image'), 'public');

            //S3へのファイルアップロード処理の時の情報が格納された変数$upload_infoを用いてアップロードされた画像へのリンクURLを変数$pathに格納する
            // $path = Storage::disk('s3')->url($upload_info);
            // $spot->spot_image = $path;
        }
        $spot->save();

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
