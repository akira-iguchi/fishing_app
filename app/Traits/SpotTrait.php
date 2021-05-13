<?php

namespace App\Traits;

use App;
use App\Models\Spot;
use App\Models\SpotImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\SpotRequest;
use Illuminate\Support\Facades\Route;

trait SpotTrait
{
    // 釣りスポットの画像保存
    public static function imageUpload($spot, $req, $image)
    {
        $spotImage = new SpotImage;
        $spotImage->spot_id = $spot->id;
        $upload_info = Storage::disk('s3')->putFile('/spot', $req->file($image), 'public');
        $path = Storage::disk('s3')->url($upload_info);
        $spotImage->spot_image = $path;
        $spotImage->save();
    }


    public static function imageUploadByCase($spot, $req, $spotImage)
    {
        $image1 = 'spot_image_first';
        $image2 = 'spot_image_second';
        $image3 = 'spot_image_third';

        $spotImage->spot_id = $spot->id;

        if ($req->hasFile('spot_image_first')
            || $req->hasFile('spot_image_second')
            || $req->hasFile('spot_image_third')
        ) {
            $spot->spotImages()->delete();
            if ($req->hasFile('spot_image_first')
                && $req->hasFile('spot_image_second')
                && $req->hasFile('spot_image_third')
            ) {
                SpotTrait::imageUpload($spot, $req, $image1);
                SpotTrait::imageUpload($spot, $req, $image2);
                SpotTrait::imageUpload($spot, $req, $image3);
            } elseif ($req->hasFile('spot_image_first') && $req->hasFile('spot_image_second')) {
                SpotTrait::imageUpload($spot, $req, $image1);
                SpotTrait::imageUpload($spot, $req, $image2);
            } elseif ($req->hasFile('spot_image_second') && $req->hasFile('spot_image_third')) {
                SpotTrait::imageUpload($spot, $req, $image2);
                SpotTrait::imageUpload($spot, $req, $image3);
            } elseif ($req->hasFile('spot_image_first') && $req->hasFile('spot_image_third')) {
                SpotTrait::imageUpload($spot, $req, $image1);
                SpotTrait::imageUpload($spot, $req, $image3);
            } elseif ($req->hasFile('spot_image_first')) {
                SpotTrait::imageUpload($spot, $req, $image1);
            } elseif ($req->hasFile('spot_image_second')) {
                SpotTrait::imageUpload($spot, $req, $image2);
            } else {
                SpotTrait::imageUpload($spot, $req, $image3);
            }
        } else {
            if (Route::currentRouteName() === 'spots.update') {
                return false;
            }
            $spotImage->save();
        }
    }
}
