<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Requests\UserRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
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

    public function show(User $user)
    {
        return view('users.show', [
            'user' => $user,
        ]);
    }

    public function followings(User $user)
    {
        $followings = $user->followings->sortByDesc('created_at')->load('followings','followers');

        return view('users.followings', [
            'user' => $user->load('spots', 'favoriteSpots'),
            'followings' => $followings,
        ]);
    }

    public function followers(User $user)
    {
        $followers = $user->followers->sortByDesc('created_at');

        return view('users.followers', [
            'user' => $user->load('followers.followers'),
            'followings' => $followings,
        ]);
    }

    public function tabs(User $user)
    {
        return $user->spots->sortByDesc('created_at')
                    ->load(['user', 'spot_favorites', 'spot_comments']);
    }
}