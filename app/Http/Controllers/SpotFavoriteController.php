<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Spot;
use App\Models\User;
use App\Models\SpotFavorite;
use Illuminate\Support\Facades\Auth;

class SpotFavoriteController extends Controller
{
    public function store(Spot $spot)
    {
        Auth::user()->favoriteSpots()->attach($spot);
    }


    public function delete(Spot $spot)
    {
        Auth::user()->favoriteSpots()->detach($spot);
    }
}
