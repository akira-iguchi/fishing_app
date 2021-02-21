<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SpotFavoriteController extends Controller
{
    /**
     *
     * @param
     * @return \Illuminate\Http\Response
     */
    public function store($id)
    {
        \Auth::user()->favorite($id);
        // 前のURLへリダイレクトさせる
        return back();
    }

    /**
     *
     * @param
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        \Auth::user()->unfavorite($id);
        // 前のURLへリダイレクトさせる
        return back();
    }
}
