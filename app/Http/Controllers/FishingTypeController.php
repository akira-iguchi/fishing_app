<?php

namespace App\Http\Controllers;

use App\Models\Spot;
use App\Models\FishingType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class FishingTypeController extends Controller
{
    public function index()
    {
        $fishing_types = FishingType::all()->sortByDesc('created_at')->load('spots');

        return view('fishing_types.index', [
            'fishing_types' => $fishing_types,
        ]);
    }

    public function edit(FishingType $fishing_type)
    {
        return view('fishing_types.edit', [
            'fishing_type' => $fishing_type,
        ]);
    }
    public function update(Request $request, FishingType $fishing_type)
    {
        $fishing_type->fill($request->except('fishing_type_image'));
        if ($request->hasFile('fishing_type_image')) {
            Storage::delete('public' . $fishing_type->fishing_type_image);
            $filePath = $request->file('fishing_type_image')->store('public');
            $fishing_type->fishing_type_image = basename($filePath);
            // $upload_info = Storage::disk('s3')->putFile('/test', $request->file('spot_image'), 'public');

            //S3へのファイルアップロード処理の時の情報が格納された変数$upload_infoを用いてアップロードされた画像へのリンクURLを変数$pathに格納する
            // $path = Storage::disk('s3')->url($upload_info);
            // $spot->spot_image = $path;
        }
        $fishing_type->save();

        return redirect()->route('fishing_types.edit', [$fishing_type]);
    }
}
