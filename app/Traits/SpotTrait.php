<?php

namespace App\Traits;

use App;
use App\Models\Spot;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

trait SpotTrait
{
    //すべての釣りスポット一覧
    public static function allSpots()
    {
        $spots = Spot::all()->sortByDesc('id')
                    ->load(['user', 'spot_favorites', 'spot_comments']);
        return $spots;
    }
}