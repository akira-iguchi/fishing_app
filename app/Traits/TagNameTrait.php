<?php

namespace App\Traits;

use App;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

trait TagNameTrait
{
    //タグの自動補完
    public static function getAllTagNames()
    {
        $allTagNames = Tag::all()->map(function ($tag) {
            return ['text' => $tag->name];
        });
        return $allTagNames;
    }
}