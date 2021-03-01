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

Route::get('/', 'SpotsController@index');

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
        Route::put('/follow', 'UsersController@follow')->name('users.follow');
        Route::delete('/follow', 'UsersController@unfollow')->name('users.unfollow');
        Route::get('/favorites', 'UsersController@favorites')->name('users.favorites');
    });

    Route::group(['prefix' => 'spots/{spot}'], function () {
        Route::put('/favorite', 'SpotsController@favorite')->name('spots.favorite');
        Route::delete('/favorite', 'SpotsController@unfavorite')->name('spots.unfavorite');
        Route::get('favorites', 'SpotsController@favorites')->name('spots.favorites');
        Route::resource('comments', 'SpotCommentController', ['only' => ['store', 'destroy']]);
    });

    Route::get('/tags/{name}', 'TagController@show')->name('tags.show');

    Route::resource('users', 'UsersController');

    Route::get('/spots/search','SpotsController@search');
    Route::resource('/spots', 'SpotsController')->except(['index']);
});