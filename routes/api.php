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

Route::get('/', 'SpotController@index')->name('spots.index');

Route::post('signup', 'Auth\RegisterController@register')->name('signup');
Route::post('login', 'Auth\LoginController@login')->name('login');
Route::post('logout', 'Auth\LoginController@logout')->name('logout');
Route::get('/user', function() {
    return Auth::user();
})->name('user');

// トークンリフレッシュ
Route::get('/reflesh-token', function (Request $request) {
    $request->session()->regenerateToken();

    return response()->json();
});

# ゲストユーザーログイン
Route::post('guest', 'Auth\LoginController@guestLogin')->name('guestLogin');

Route::group(['middleware' => ['auth']], function () {

    Route::group(['prefix' => 'users/{id}'], function () {
        Route::put('/follow', 'FollowController@follow')->name('users.follow');
        Route::delete('/follow', 'FollowController@unfollow')->name('users.unfollow');

        // ユーザーページのタブ
        Route::get('/spots', 'UserTabController@spots');
        Route::get('/favoriteSpots', 'UserTabController@favoriteSpots');
        Route::get('/followings', 'UserTabController@followings');
        Route::get('/followers', 'UserTabController@followers');

         // カレンダー機能
        Route::resource('/events', 'EventController');
        Route::put('/editEventDate', 'EventController@editEventDate')->name('editEventDate');
    });

    Route::group(['prefix' => 'spots/{id}'], function () {
        Route::put('/favorite', 'SpotFavoriteController@favorite')->name('spots.favorite');
        Route::delete('/favorite', 'SpotFavoriteController@unfavorite')->name('spots.unfavorite');
        Route::post('comments', 'SpotCommentController@store')->name('spots.comment');
        Route::delete('comments/{id}', 'SpotCommentController@destroy')->name('comment.delete');
    });

    Route::get('/tags/{name}', 'TagController')->name('tags');

    Route::resource('users', 'UserController')->except(['index']);;

    Route::get('/fishing_types','FishingTypeController');

    Route::get('/weathers/{city}','WeatherController');

    Route::get('/spots/search','SpotController@search');

    Route::resource('/spots', 'SpotController')->except(['index']);
});