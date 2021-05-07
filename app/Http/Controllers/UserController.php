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
        return $user;
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

            return response($user, 201);
        });
    }

    public function show(String $id)
    {
        $user = User::findOrFail($id)->load([
                'spots.spotImages',
                'spots.spotComments',
                'spots.spotFavorites',
                'favoriteSpots.spotImages',
                'favoriteSpots.spotComments',
                'favoriteSpots.spotFavorites',
                'followings',
                'followers'
            ]);

        return $user;
    }
}
