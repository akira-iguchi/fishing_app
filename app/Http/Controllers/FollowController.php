<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Auth;

class FollowController extends Controller
{
    public function follow(String $id)
    {
        $user = User::where('id', $id)->first();

        if (! $user) {
            abort(404);
        }

        Auth::user()->followings()->detach($user);
        Auth::user()->followings()->attach($user);

        return ["user_id" => $id];
    }

    public function unfollow(String $id)
    {
        $user = User::where('id', $id)->first();

        if (! $user) {
            abort(404);
        }

        Auth::user()->followings()->detach($user);

        return ["user_id" => $id];
    }
}
