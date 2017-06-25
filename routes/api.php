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

Route::prefix('restaurants')
    ->middleware('auth:api')
    ->namespace('Api')
    ->group(function () {
        Route::post('/', 'RestaurantController@register');
        Route::get('/{id}', 'RestaurantController@get');
});

Route::prefix('cycles')
    ->middleware('auth:api')
    ->namespace('Api')
    ->group(function () {
        Route::post('/', 'CycleController@createCycle');
});
