<?php

namespace App\Http\Controllers;

use App\Models\Spot;
use App\Models\Tag;
use App\Models\SpotComment;
use Illuminate\Http\Request;
use App\Traits\TagNameTrait;
use App\Http\Requests\SpotRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class SpotsController extends Controller
{
    // 共通メソッド化（挑戦中）
    use TagNameTrait;

    public function index()
    {
        $spots = Spot::all()->sortByDesc('created_at')->load('user');

        $tags = Tag::all();

        return view('spots.index', [
            'spots' => $spots,
            'tags' => $tags,
        ]);
    }

    public function show(Spot $spot)
    {
        // その他の釣りスポット
        $spots = Spot::where('id','!=', $spot->id)->get()->sortByDesc('created_at')->load('user');

        // メッセージ詳細ビューでそれを表示
        return view('spots.show', [
            'spot' => $spot->load('user'),
            'spots' => $spots,
        ]);
    }

    public function create(Spot $spot)
    {
        $allTagNames = Tag::all()->map(function ($tag) {
            return ['text' => $tag->name];
        });

        return view('spots.create', [
            'spot' => $spot,
            'allTagNames' => $allTagNames,
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

        return redirect('/')->with('flash_message', '釣りスポットを投稿しました');
    }

    public function edit(Spot $spot)
    {
        if (\Auth::id() === $spot->user_id) {
            $tagNames = $spot->tags->map(function ($tag) {
                return ['text' => $tag->name];
            });

            $allTagNames = Tag::all()->map(function ($tag) {
                return ['text' => $tag->name];
            });

            return view('spots.edit', [
                'spot' => $spot,
                'tagNames' => $tagNames,
                'allTagNames' => $allTagNames,
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
        $tags = Tag::all();

        if (!empty($keyword_name)) {
            $query = Spot::query();
            $spots = $query->where('spot_name','like', '%' .$keyword_name. '%')->get();
            return view('spots/search')->with([
            'spots' => $spots,
            'keyword_name' => $keyword_name,
            'tags' => $tags,
            ]);
        } else {
            $spots = Spot::all()->sortByDesc('created_at')->load('user');
            return view('spots.search', [
                'spots' => $spots,
                'tags' => $tags,
            ]);
        }
    }

    public function favorite(Request $request, Spot $spot)
    {
        // 1人のユーザーが同一釣りスポットに複数回重ねていいねを付けられないようにするため、先にdetach
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
