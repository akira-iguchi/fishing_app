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
        $fishing_types = FishingType::all()->sortByDesc('created_at')->load('spots');

        return view('fishing_types.index', [
            'fishing_types' => $fishing_types,
        ]);
    }
}
