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
        $tags = Tag::all();
        $cardSize = 'mx-auto d-block col-lg-4 col-md-6 col-11';

        return view('tags.show', [
            'tag' => $tag,
            'tags' => $tags,
            'cardSize' => $cardSize,
        ]);
    }
}
