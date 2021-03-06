<?php

namespace App\Traits;

use App;
use App\Models\Spot;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

trait SpotTrait
{
    //タグの自動補完
    public static function allSpots()
    {
        $spots = Spot::all()->sortByDesc('created_at')
                    ->load(['user', 'spot_favorites', 'spot_comments']);
        return $spots;
    }
}