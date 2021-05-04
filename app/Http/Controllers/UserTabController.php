<?php

namespace App\Http\Controllers;

use App\Models\User;

class UserTabController extends Controller
{
    public function spots(String $id)
    {
        $user = User::findOrFail($id);

        return $user->spots->sortByDesc('id')->values()
            ->load(['user', 'spotImages', 'spotFavorites', 'spotComments']);
    }

    public function favoriteSpots(String $id)
    {
        $user = User::findOrFail($id);

        return $user->favoriteSpots->sortByDesc('id')->values()
            ->load(['user', 'spotImages', 'spotFavorites', 'spotComments']);
    }

    public function followings(String $id)
    {
        $user = User::findOrFail($id);

        return $user->followings->sortByDesc('id')->values()
            ->load('followings', 'followers');
    }

    public function followers(String $id)
    {
        $user = User::findOrFail($id);

        return $user->followers->sortByDesc('id')->values()
            ->load('followings', 'followers');
    }
}
