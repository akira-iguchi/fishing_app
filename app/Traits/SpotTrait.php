<?php

namespace App\Traits;

use App;
use App\Models\Spot;
use App\Models\SpotImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\SpotRequest;

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


    public static function imageUploadCase($spot, $req, $spot_image)
    {
        $image1 = 'spot_image1';
        $image2 = 'spot_image2';
        $image3 = 'spot_image3';

        $spot_image->spot_id = $spot->id;

        if ($req->hasFile('spot_image1') || $req->hasFile('spot_image2') || $req->hasFile('spot_image3')) {
            if ($req->hasFile('spot_image1')
                && $req->hasFile('spot_image2')
                && $req->hasFile('spot_image3')
            ) {
                SpotTrait::imageUpload($spot, $req, $image1);
                SpotTrait::imageUpload($spot, $req, $image2);
                SpotTrait::imageUpload($spot, $req, $image3);
            } elseif ($req->hasFile('spot_image1') && $req->hasFile('spot_image2')) {
                SpotTrait::imageUpload($spot, $req, $image1);
                SpotTrait::imageUpload($spot, $req, $image2);
            } elseif ($req->hasFile('spot_image2') && $req->hasFile('spot_image3')) {
                SpotTrait::imageUpload($spot, $req, $image2);
                SpotTrait::imageUpload($spot, $req, $image3);
            } elseif ($req->hasFile('spot_image1') && $req->hasFile('spot_image3')) {
                SpotTrait::imageUpload($spot, $req, $image1);
                SpotTrait::imageUpload($spot, $req, $image3);
            } elseif ($req->hasFile('spot_image1')) {
                SpotTrait::imageUpload($spot, $req, $image1);
            } elseif ($req->hasFile('spot_image2')) {
                SpotTrait::imageUpload($spot, $req, $image2);
            } else {
                SpotTrait::imageUpload($spot, $req, $image3);
            }
        } else {
            $spot_image->save();
        }
    }
}
