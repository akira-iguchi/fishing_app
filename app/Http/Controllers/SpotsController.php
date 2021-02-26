<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\SpotRequest;
use App\Models\Spot;
use App\Models\SpotComment;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class SpotsController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Spot::class, 'spot');
    }

    public function index()
    {
        $spots = Spot::all();
        return view('spots.index', ['spots' => $spots]);
    }

    public function show(Spot $spot)
    {
        // その他の釣りスポット
        $spots = Spot::all();

        $comments = $spot->spot_comments()->orderBy('created_at', 'desc')->paginate(11);

        // メッセージ詳細ビューでそれを表示
        return view('spots.show', [
            'spot' => $spot,
            'spots' => $spots,
            'comments' => $comments,
        ]);
    }

    public function create(Spot $spot)
    {
        return view('spots.create', [
            'spot' => $spot,
        ]);
    }

    public function store(SpotRequest $request, Spot $spot) {

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
        return redirect('/')->with('flash_message', '釣りスポットを投稿しました');
    }

    public function edit(Spot $spot)
    {

        if (\Auth::id() === $spot->user_id) {
            return view('spots.edit', [
                'spot' => $spot,
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

        if (!empty($keyword_name)) {
            $query = Spot::query();
            $spots = $query->where('spot_name','like', '%' .$keyword_name. '%')->get();
            return view('spots/search')->with([
            'spots' => $spots
            ]);
        } else {
            $spots = Spot::all();
            return view('spots.index', ['spots' => $spots]);
        }
    }

}
