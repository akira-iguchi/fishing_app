<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\UserRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    public function edit(User $user)
    {
        if ($user->id == 1) {
            session()->flash('error_message', 'ゲストユーザーは編集できません');
            return redirect('/');
        } else {
            return view('users.edit', ['user' => Auth::user() ]);
        }
    }

    public function update(UserRequest $request, User $user)
    {
        return DB::transaction(function () use ($user, $request) {
            if ($request->hasFile('user_image')) {
                Storage::delete('public' . $user->user_image);
                $upload_info = Storage::disk('s3')->putFile('/user', $request->file('user_image'), 'public');
                $path = Storage::disk('s3')->url($upload_info);
                $user->user_image = $path;
            }
            $user->fill($request->except('user_image'))->save();

            session()->flash('flash_message', 'ユーザー情報を更新しました');
            return redirect()->route('users.show', [$user]);
        });
    }

    public function follow(String $id)
    {
        $user = User::where('id', $id)->with('followings')->first();

        $user->followings()->detach($user);
        $user->followings()->attach($user);

        return ["user_id" => $id];
    }

    public function unfollow(Request $request, User $user)
    {
        $request->user()->followings()->detach($user);

        return [
            'user' => $user,
            'countFollowings' => $user->count_followings,
            'countFollowers' => $user->count_followers,
        ];
    }

    public function show(String $id)
    {
        $user = User::findOrFail($id)
            ->load(
                ['followings', 'followers']
            );

        return $user ?? abort(404);
    }
}
