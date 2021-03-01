<?php

namespace App\Http\Controllers;

use App\Models\Tag;
use App\Models\Spot;
use Illuminate\Http\Request;

class TagController extends Controller
{
    public function show(string $name)
    {
        $tag = Tag::where('name', $name)->first();

        return view('tags.show', ['tag' => $tag]);
    }
}
