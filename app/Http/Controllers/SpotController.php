<?php

namespace App\Http\Controllers;

use App\Models\Spot;
use App\Models\Tag;
use App\Models\FishingType;
use App\Models\SpotComment;
use Illuminate\Http\Request;
use App\Traits\TagNameTrait;
use App\Traits\SpotTrait;
use App\Http\Requests\SpotRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class SpotController extends Controller
{
    public function index()
    {
        if (Auth::check()) {
            $spots = SpotTrait::allSpots();

            $tags = Tag::all()->take(15);

            return view('spots.index', [
                'spots' => $spots,
                'tags' => $tags,
            ]);
        } else {
            return view('spots.index');
        }
    }

    public function show(Spot $spot)
    {
        // その他の釣りスポット
        $spots = Spot::where('id','!=', $spot->id)->get()->sortByDesc('created_at')
                ->take(15)
                ->load(['user', 'spot_favorites', 'spot_comments', 'fishing_types']);

        // メッセージ詳細ビューでそれを表示
        return view('spots.show', [
            'spot' => $spot,
            'spots' => $spots,
        ]);
    }

    public function create(Spot $spot)
    {
        $allTagNames = TagNameTrait::getAllTagNames();

        $fishingTypes = FishingType::all();

        return view('spots.create', [
            'spot' => $spot,
            'allTagNames' => $allTagNames,
            'fishingTypes' => $fishingTypes,
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
            $tag = Tag::firstOrCreate(['text' => $tagName]);
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

            $fishingTypes = FishingType::all();

            return view('spots.edit', [
                'spot' => $spot,
                'tagNames' => $tagNames,
                'allTagNames' => $allTagNames,
                'fishingTypes' => $fishingTypes,
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

        $request->tags->each(function ($tagName) use ($spot) {
            $tag = Tag::firstOrCreate(['name' => $tagName]);
            $spot->tags()->sync($tag);
        });

        $spot->fishing_types()->sync($request->fishing_types);

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

    public function search(Request $request) {
        $keyword_name = $request->name;
        $tags = Tag::all()->take(15);

        if (!empty($keyword_name)) {
            $spots = Spot::query()->where('spot_name','like', '%' .$keyword_name. '%')->get();
            return view('spots.searches.search')->with([
                'spots' => $spots->sortByDesc('created_at')
                            ->load(['user', 'spot_favorites', 'spot_comments']),
                'keyword_name' => $keyword_name,
                'cardSize' => $cardSize,
                'tags' => $tags,
            ]);
        } else {
            $spots = SpotTrait::allSpots();
            return view('spots.searches.search', [
                'spots' => $spots,
                'tags' => $tags,
            ]);
        }
    }

    public function favorite(Request $request, Spot $spot)
    {
        $spot->spot_favorites()->sync($request->user()->id);

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
