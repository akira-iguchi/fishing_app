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
        return view('users.edit', ['user' => Auth::user() ]);
    }

    public function update(Request $request, $id)
    {
        $form = $request->all();

        // バリデーション
        $rules = [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'image' => 'nullable|image'
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
            if ($request->hasFile('image')) {
                Storage::delete('public' . $user->image);
                $filePath = $request->file('image')->store('public');
                $user->image = basename($filePath);
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
}