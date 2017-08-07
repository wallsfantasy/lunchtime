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

/* Restaurant */
Route::group(['prefix' => 'restaurant'], function () {
    Route::get('/', 'Web\RestaurantController@index')->name('restaurant');
    Route::get('/search', 'Web\RestaurantController@search')->name('restaurant-search');
    Route::post('/register', 'Web\RestaurantController@postRegisterRestaurant')->name('restaurant-register');
});
