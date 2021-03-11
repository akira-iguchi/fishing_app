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

    // idはフォローされる側
    Route::group(['prefix' => 'users/{user}'], function () {
        Route::put('/follow', 'UserController@follow')->name('users.follow');
        Route::delete('/follow', 'UserController@unfollow')->name('users.unfollow');
        Route::get('/tabs', 'UserController@tabs')->name('users.tabs');
    });

    Route::group(['prefix' => 'spots/{spot}'], function () {
        Route::put('/favorite', 'SpotController@favorite')->name('spots.favorite');
        Route::delete('/favorite', 'SpotController@unfavorite')->name('spots.unfavorite');
        Route::get('favorites', 'SpotController@favorites')->name('spots.favorites');
        Route::get('comments', 'SpotCommentController@index');
        Route::post('comments', 'SpotCommentController@store');
        Route::delete('comments/{comment}', 'SpotCommentController@destroy');
    });

    Route::get('/tags/{name}', 'TagController@show')->name('tags.show');

    Route::resource('users', 'UserController');

    Route::get('/fishing_types','FishingTypeController@index');

    Route::get('/spots/search','SpotController@search');
    Route::resource('/spots', 'SpotController')->except(['index']);
});