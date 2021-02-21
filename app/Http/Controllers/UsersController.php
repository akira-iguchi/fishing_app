<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class UsersController extends Controller
{
    public function show($id)
    {
        // idの値でメッセージを検索して取得
        $user = User::findOrFail($id);

        // メッセージ詳細ビューでそれを表示
        return view('users.show', [
            'user' => $user,
        ]);
    }

    public function edit($id)
    {
        $user = User::findOrFail($id);

        if($user->id == 1) {
            session()->flash('error_message', 'ゲストユーザーは編集できません');
            return redirect('/spots');
        } else {
            return view('users.edit', ['user' => Auth::user() ]);
        }
    }

    public function update(Request $request, $id)
    {
        $form = $request->all();

        // バリデーション
        $rules = [
            'name' => 'required|string|max:10',
            'email' => 'required|string|email|max:255|unique:users,email,'.Auth::user()->email.',email',
            'user_image' => 'nullable|image'
        ];

        $validator = Validator::make($form, $rules);

        if($validator->fails()){
            session()->flash('error_message', '入力に不備があります');
            return back()
                ->withErrors($validator)
                ->withInput();
        }else{
            $user = Auth::user();
            $user->name = $request->name;
            $user->email = $request->email;
            if ($request->hasFile('user_image')) {
                Storage::delete('public' . $user->user_image);
                $filePath = $request->file('user_image')->store('public');
                $user->user_image = basename($filePath);
                // $upload_info = Storage::disk('s3')->putFile('/test', $request->file('image'), 'public');

                //S3へのファイルアップロード処理の時の情報が格納された変数$upload_infoを用いてアップロードされた画像へのリンクURLを変数$pathに格納する
                // $path = Storage::disk('s3')->url($upload_info);
                // $user->image = $path;
            }
            $user->save();
            session()->flash('flash_message', 'ユーザー情報を更新しました');
            return redirect()->route('users.show', [$user]);
        }
    }

    /**
     * ユーザのフォロー一覧ページを表示するアクション。
     *
     * @param  $id  ユーザのid
     * @return \Illuminate\Http\Response
     */
    public function followings($id)
    {
        // idの値でユーザを検索して取得
        $user = User::findOrFail($id);

        // 関係するモデルの件数をロード
        $user->loadRelationshipCounts();

        // ユーザのフォロー一覧を取得
        $followings = $user->followings()->paginate(10);

        // フォロー一覧ビューでそれらを表示
        return view('users.followings', [
            'user' => $user,
            'users' => $followings,
        ]);
    }

    /**
     * ユーザのフォロワー一覧ページを表示するアクション。
     *
     * @param  $id  ユーザのid
     * @return \Illuminate\Http\Response
     */
    public function followers($id)
    {
        // idの値でユーザを検索して取得
        $user = User::findOrFail($id);

        // 関係するモデルの件数をロード
        $user->loadRelationshipCounts();

        // ユーザのフォロワー一覧を取得
        $followers = $user->followers()->paginate(10);

        // フォロワー一覧ビューでそれらを表示
        return view('users.followers', [
            'user' => $user,
            'users' => $followers,
        ]);
    }
}
