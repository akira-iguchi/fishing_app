<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::group(['middleware' => ['api']], function () {
    Route::get('comments', 'SpotCommentApiController@index');
    Route::post('spots/{spot}/comments', 'SpotCommentApiController@store');
    Route::delete('spots/{spot}/comments/{comment}', 'SpotCommentApiController@destroy');
});