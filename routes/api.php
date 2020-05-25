<?php

use Illuminate\Http\Request;

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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group(['prefix' => 'v1'], function () {
    Route::post("/login", "Api\V1\AuthController@login");
    Route::post("/register", "Api\V1\AuthController@register");
});

Route::group(['prefix' => 'v1', 'middleware' => 'auth:api'], function () {
    Route::get('/selectTrack', "Api\V1\TrackController@getTrack");
    Route::post('/uploadTracks', "Api\V1\TrackController@uploadTrack");
    Route::get('/getPlaylist', "Api\V1\PlaylistController@getPlaylist");
    Route::post('/setPlaylist', "Api\V1\PlaylistController@setPlaylist");
    Route::post('/delTrackPlaylist', "Api\V1\PlaylistController@delTrackPlaylist");
});
