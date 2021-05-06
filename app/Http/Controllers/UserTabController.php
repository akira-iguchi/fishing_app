<?php

namespace App\Http\Controllers;

use App\Models\User;

class UserTabController extends Controller
{
    public function spots(String $id)
    {
        $user = User::findOrFail($id);

        return $user->spots()
            ->with(['user', 'spotImages', 'spotFavorites', 'spotComments'])
            ->latest()->get();
    }

    public function favoriteSpots(String $id)
    {
        $user = User::findOrFail($id);

        return $user->favoriteSpots()
            ->with(['user', 'spotImages', 'spotFavorites', 'spotComments'])
            ->latest()->get();
    }

    public function followings(String $id)
    {
        $user = User::findOrFail($id);

        return $user->followings()->with('followings', 'followers')
            ->latest()->get();
    }

    public function followers(String $id)
    {
        $user = User::findOrFail($id);

        return $user->followers()->with('followings', 'followers')
            ->latest()->get();
    }
}
