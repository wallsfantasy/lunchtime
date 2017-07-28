<?php

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

Route::prefix('me')
    ->middleware('auth:api')
    ->group(function () {
        Route::get('/', 'Api\UserController@getMyUser');
        Route::get('/members', 'Api\UserController@getMyMembers');
        Route::get('/cycles', 'Api\UserController@getMyCycles');
    });

Route::prefix('restaurants')
    ->middleware('auth:api')
    ->group(function () {
        Route::post('/', 'Api\RestaurantController@register');
        Route::get('/{id}', 'Api\RestaurantController@get');
    });

Route::prefix('cycles')
    ->middleware('auth:api')
    ->group(function () {
        Route::get('/', 'Api\CycleController@get');
        Route::get('/{cycleId}', 'Api\CycleController@getById');
        Route::post('/', 'Api\CycleController@create');
        Route::post('/{cycleId}/join', 'Api\CycleController@join');
        Route::delete('/{cycleId}/leave', 'Api\CycleController@leave');
    });

Route::prefix('proposes')
    ->middleware('auth:api')
    ->group(function () {
        Route::post('/', 'Api\ProposeController@make');
    });
