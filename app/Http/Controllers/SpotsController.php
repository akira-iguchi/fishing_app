<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Spot;
use App\Models\SpotComment;
use Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class SpotsController extends Controller
{

    public function index()
    {
        $spots = Spot::all();
        return view('spots.index', ['spots' => $spots]);
    }

    public function show($id)
    {
        // idの値でメッセージを検索して取得
        $spot = Spot::findOrFail($id);

        $spots = Spot::all();

        $comments = $spot->spot_comments()->orderBy('created_at', 'desc')->paginate(11);

        // メッセージ詳細ビューでそれを表示
        return view('spots.show', [
            'spot' => $spot,
            'spots' => $spots,
            'comments' => $comments,
        ]);
    }

    public function create()
    {

        $spot = new Spot;

        // メッセージ作成ビューを表示
        return view('spots.create', [
            'spot' => $spot,
        ]);
    }

    public function store(Request $request) {
        $form = $request->all();

        // バリデーション
        $rules = [
            'name' => 'required|max:20',
            'explanation' => 'required|max:300',
            'address' => 'max:50',
            'latitude' => 'required',
            'longitude' => 'required',
            'image' => 'nullable|image'
        ];

        $spot = [
            'name.required'=> '釣り場名を入力してください',
            'explanation.required'=> '説明を入力してください',
        ];

        $validator = Validator::make($form, $rules, $spot);

        if($validator->fails()){
            session()->flash('error_message', '入力に不備があります');
            return redirect('/spots/create')
                ->withErrors($validator)
                ->withInput();
        }else{
            $spot = new Spot;
            $spot->name = $request->input('name');
            $spot->explanation = $request->input('explanation');
            $spot->address = $request->input('address');
            if ($request->hasFile('image')) {
                $filePath = $request->file('image')->store('public');
                $spot->image = basename($filePath);
                // $upload_info = Storage::disk('s3')->putFile('/test', $request->file('image'), 'public');

                //S3へのファイルアップロード処理の時の情報が格納された変数$upload_infoを用いてアップロードされた画像へのリンクURLを変数$pathに格納する
                // $path = Storage::disk('s3')->url($upload_info);
                // $spot->image = $path;
            }
            $spot->latitude = $request->input('latitude');
            $spot->longitude = $request->input('longitude');
            $spot->user_id = auth()->id();
            $spot->save();
            return redirect('/spots')->with('flash_message', '釣りスポットを投稿しました');
        }
    }

    public function edit($id)
    {
        $spot = Spot::findOrFail($id);

        if (\Auth::id() === $spot->user_id) {
            return view('spots.edit', [
                'spot' => $spot,
            ]);
        } else {
            session()->flash('error_message', '自信が投稿した釣りスポットのみ編集できます');
            return redirect('/spots');
        }
    }

    public function update(Request $request, $id)
    {
        $form = $request->all();

        // バリデーション
        $rules = [
            'name' => 'required|max:20',
            'explanation' => 'required|max:300',
            'address' => 'max:50',
            'latitude' => 'required',
            'longitude' => 'required',
            'image' => 'nullable|image'
        ];

        $spot = [
            'name.required'=> '釣り場名を入力してください',
            'explanation.required'=> '説明を入力してください',
        ];

        $validator = Validator::make($form, $rules, $spot);

        if($validator->fails()){
            session()->flash('error_message', '入力に不備があります');
            return back()
                ->withErrors($validator)
                ->withInput();
        }else{
            $spot = Spot::findOrFail($id);
            $spot->name = $request->name;
            $spot->explanation = $request->explanation;
            $spot->address = $request->address;
            if ($request->hasFile('image')) {
                Storage::delete('public' . $spot->image);
                $filePath = $request->file('image')->store('public');
                $spot->image = basename($filePath);
                // $upload_info = Storage::disk('s3')->putFile('/test', $request->file('image'), 'public');

                //S3へのファイルアップロード処理の時の情報が格納された変数$upload_infoを用いてアップロードされた画像へのリンクURLを変数$pathに格納する
                // $path = Storage::disk('s3')->url($upload_info);
                // $spot->image = $path;
            }
            $spot->latitude = $request->latitude;
            $spot->longitude = $request->longitude;
            $spot->user_id = auth()->id();
            $spot->save();
            session()->flash('flash_message', '釣りスポットを更新しました');
            return redirect()->route('spots.show', [$spot]);
        }
    }

    public function destroy($id)
    {
        // idの値で投稿を検索して取得
        $spot = Spot::findOrFail($id);

        // 認証済みユーザ（閲覧者）がその投稿の所有者である場合は、投稿を削除
        if (\Auth::id() === $spot->user_id) {
            $spot->delete();
        }

        return redirect('/spots')->with('flash_message', '釣りスポットを削除しました');
    }

    public function search(Request $request) {
        $keyword_name = $request->name;

        if (!empty($keyword_name)) {
            $query = Spot::query();
            $spots = $query->where('name','like', '%' .$keyword_name. '%')->get();
            return view('spots/search')->with([
            'spots' => $spots
            ]);
        } else {
            $spots = Spot::all();
            return view('spots.index', ['spots' => $spots]);
        }
    }

}
