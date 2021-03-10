<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Requests\UserRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    public function show(User $user)
    {
        // メッセージ詳細ビューでそれを表示
        return view('users.show', [
            'user' => $user,
            'spots' => $user->spots->sortByDesc('created_at')
                        ->load(['user', 'spot_favorites', 'spot_comments']),
        ]);
    }

    public function edit(User $user)
    {
        if($user->id == 1) {
            session()->flash('error_message', 'ゲストユーザーは編集できません');
            return redirect('/');
        } else {
            return view('users.edit', ['user' => Auth::user() ]);
        }
    }

    public function update(UserRequest $request, User $user)
    {
        $user->fill($request->except('user_image'));
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

    public function follow(Request $request, User $user)
    {
        if ($user->id === $request->user()->id)
        {
            return abort('404', 'あなた自信をフォローすることはできません');
        }

        $request->user()->followings()->detach($user);
        $request->user()->followings()->attach($user);

        return [
            'user' => $user,
            'countFollowings' => $user->count_followings,
            'countFollowers' => $user->count_followers,
        ];
    }

    public function unfollow(Request $request, User $user)
    {
        if ($user->id === $request->user()->id)
        {
            return abort('404', 'あなた自信をフォローすることはできません');
        }

        $request->user()->followings()->detach($user);

        return [
            'user' => $user,
            'countFollowings' => $user->count_followings,
            'countFollowers' => $user->count_followers,
        ];
    }

    public function favorites(User $user)
    {
        return view('users.favorite_spots', [
            'user' => $user,
            'spots' => $user->favoriteSpots->sortByDesc('created_at')
                        ->load(['user', 'spot_favorites', 'spot_comments']),
        ]);
    }
}
