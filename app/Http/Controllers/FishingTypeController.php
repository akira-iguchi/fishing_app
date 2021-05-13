<?php

namespace App\Http\Controllers;

use App\Models\Spot;
use App\Models\FishingType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class FishingTypeController extends Controller
{
    public function __invoke()
    {
        $fishingTypes = FishingType::with([
            'spots.spotImages',
            'spots.spotComments',
            'spots.spotFavorites',
        ])->latest()->get();

        return $fishingTypes;
    }
}
