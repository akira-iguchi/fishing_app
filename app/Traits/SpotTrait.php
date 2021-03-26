<?php

namespace App\Traits;

use App;
use App\Models\Spot;
use App\Models\SpotImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

trait SpotTrait
{
    // すべての釣りスポット一覧
    public static function allSpots()
    {
        $spots = Spot::all()->sortByDesc('id')
                    ->load(['user', 'spot_favorites', 'spot_comments']);
        return $spots;
    }

    // 釣りスポットの画像保存
    public static function imageUpload($spot, $req, $image)
    {
        $spot_image = new SpotImage;
        $spot_image->spot_id = $spot->id;
        $filePath = $req->file($image)->store('public');
        $spot_image->spot_image = basename($filePath);
        $spot_image->save();
    }
}