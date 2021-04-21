<?php

namespace App\Http\Controllers;

use App\Models\User;

class UserTabController extends Controller
{
    public function spots(User $user)
    {
        return $user->spots
                    ->load(['user', 'spotImages', 'spotFavorites', 'spotComments']);
    }

    public function favoriteSpots(User $user)
    {
        return $user->favoriteSpots
                    ->load(['user', 'spotImages', 'spotFavorites', 'spotComments']);
    }

    public function followings(User $user)
    {
        return $user->followings
                    ->load('followings', 'followers');
    }

    public function followers(User $user)
    {
        return $followers = $user->followers
                                ->load('followings', 'followers');
    }
}
