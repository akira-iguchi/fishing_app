<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', 'SpotController@index');

Route::get('signup', 'Auth\RegisterController@showRegistrationForm')->name('signup.get');
Route::post('signup', 'Auth\RegisterController@register')->name('signup.post');

Route::get('login', 'Auth\LoginController@showLoginForm')->name('login');
Route::post('login', 'Auth\LoginController@login')->name('login.post');
Route::get('logout', 'Auth\LoginController@logout')->name('logout.get');

# ゲストユーザーログイン
Route::get('guest', 'Auth\LoginController@guestLogin')->name('login.guest');

Route::group(['middleware' => ['auth']], function () {

    Route::group(['prefix' => 'users/{user}'], function () {
        Route::put('/follow', 'UserController@follow')->name('users.follow');
        Route::delete('/follow', 'UserController@unfollow')->name('users.unfollow');

        // ユーザーページのタブ
        Route::get('/spots', 'UserController@spots');
        Route::get('/favoriteSpots', 'UserController@favoriteSpots');
        Route::get('/followings', 'UserController@followings');
        Route::get('/followers', 'UserController@followers');

         // カレンダー機能
        Route::get('/events', 'EventController@index')->name('events');
        Route::get('/setEvents', 'EventController@setEvents');
        Route::post('/ajax/addEvent', 'EventController@addEvent');
        Route::post('/ajax/editEventDate', 'EventController@editEventDate');
    });

    Route::group(['prefix' => 'spots/{spot}'], function () {
        Route::put('/favorite', 'SpotController@favorite')->name('spots.favorite');
        Route::delete('/favorite', 'SpotController@unfavorite')->name('spots.unfavorite');
        Route::get('favorites', 'SpotController@favorites')->name('spots.favorites');
        Route::get('comments', 'SpotCommentController@index');
        Route::post('comments', 'SpotCommentController@store');
        Route::delete('comments/{comment}', 'SpotCommentController@destroy');
    });

    // タグの釣りスポット一覧
    Route::get('/tags/{name}', 'TagController@show')->name('tags.show');

    // ユーザーの基本機能
    Route::resource('users', 'UserController');

    // 釣り方一覧
    Route::get('/fishing_types','FishingTypeController@index');

    // 釣りスポット機能
    Route::get('/spots/search','SpotController@search')->name('spots.search');
    Route::resource('/spots', 'SpotController')->except(['index']);
});