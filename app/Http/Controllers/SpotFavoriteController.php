<?php

namespace App\Http\Controllers;

use App\Models\Spot;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SpotFavoriteController extends Controller
{
    public function favorite(String $id)
    {
        $spot = Spot::where('id', $id)->with('spotFavorites')->first();

        if (! $spot) {
            abort(404);
        }

        $spot->spotFavorites()->detach(Auth::user()->id);
        $spot->spotFavorites()->attach(Auth::user()->id);

        return ["spot_id" => $id];
    }

    public function unfavorite(String $id)
    {
        $spot = Spot::where('id', $id)->with('spotFavorites')->first();

        if (! $spot) {
            abort(404);
        }

        $spot->spotFavorites()->detach(Auth::user()->id);

        // return [
        //     'spot' => $spot,
        //     'countSpotFavorites' => $spot->count_spot_favorites,
        // ];
        return ["spot_id" => $id];
    }
}
