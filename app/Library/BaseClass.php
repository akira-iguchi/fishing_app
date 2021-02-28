<?php

namespace App\Library;

use App;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BaseClass
{
    //タグの自動補完
    public static function getAllTagNames()
    {
        Tag::all()->map(function ($tag) {
            return ['text' => $tag->name];
        });
    }
}