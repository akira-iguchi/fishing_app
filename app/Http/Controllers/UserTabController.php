<?php

namespace App\Http\Controllers;

use App\Models\User;

class UserTabController extends Controller
{
    public function spots(String $id)
    {
        $user = User::findOrFail($id);
        return $user->spots->sortByDesc('id')
                    ->load(['user', 'spotImages', 'spotFavorites', 'spotComments']);

    }

    public function favoriteSpots(String $id)
    {
        $user = User::findOrFail($id);
        return $user->favoriteSpots
                    ->load(['user', 'spotImages', 'spotFavorites', 'spotComments']);
    }

    public function followings(String $id)
    {
        $user = User::findOrFail($id);
        return $user->followings
                    ->load('followings', 'followers');
    }

    public function followers(String $id)
    {
        $user = User::findOrFail($id);
        return $followers = $user->followers
                                ->load('followings', 'followers');
    }
}
