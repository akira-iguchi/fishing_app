<?php

namespace App\Traits;

use App;
use App\Models\Spot;
use App\Models\SpotImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

trait SpotTrait
{
    // 釣りスポットの画像保存
    public static function imageUpload($spot, $req, $image)
    {
        $spot_image = new SpotImage;
        $spot_image->spot_id = $spot->id;
        $upload_info = Storage::disk('s3')->putFile('/spot', $req->file($image), 'public');
        $path = Storage::disk('s3')->url($upload_info);
        $spot_image->spot_image = $path;
        $spot_image->save();
    }
}
