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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::prefix('v2')->group(function () {
    Route::prefix('snaps')->group(function () {
        Route::get('info/{name}', 'SnapController@v2_snaps_info');
        Route::get('find', 'SnapController@v2_snaps_find');
    });
});