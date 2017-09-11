<?php

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

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

/* Home */
Route::group(['middleware' => ['auth']], function () {
    Route::get('/home', 'Web\HomeController@index')->name('home');
});

/* Propose */
Route::group(['prefix' => 'propose', 'middleware' => ['auth']], function () {
    Route::get('/', 'Web\ProposeController@index')->name('propose');
    Route::post('/restaurant-search', 'Web\ProposeController@postRestaurantSearch')->name('propose-restaurant-search');
    Route::post('/make', 'Web\ProposeController@postMakePropose')->name('propose-make');
});

/* Restaurant */
Route::group(['prefix' => 'restaurant', 'middleware' => ['auth']], function () {
    Route::get('/', 'Web\RestaurantController@index')->name('restaurant');
    Route::post('/search', 'Web\RestaurantController@postSearch')->name('restaurant-search');
    Route::post('/register', 'Web\RestaurantController@postRegisterRestaurant')->name('restaurant-register');
});

/* User */
Route::group(['prefix' => 'user', 'middleware' => ['auth']], function () {
    Route::get('/', 'Web\UserController@index')->name('user');
    Route::post('/search', 'Web\UserController@postSearch')->name('user-search');
});

/* Cycle */
Route::group(['prefix' => 'cycle', 'middleware' => ['auth']], function () {
    Route::get('/', 'Web\CycleController@index')->name('cycle');
    Route::post('/', 'Web\CycleController@postCreateCycle')->name('cycle-create');
    Route::post('/search', 'Web\CycleController@postSearch')->name('cycle-search');
});
