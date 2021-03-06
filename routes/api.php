<?php

use Illuminate\Http\Request;
use App\Photo;

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

Route::get('photos', 'PhotoController@index');

Route::get('photos/search', 'PhotoController@search');

Route::get('photos/random', 'PhotoController@random');

Route::get('sync','SyncController@sync');
