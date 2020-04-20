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

Auth::routes(['register' => false]);

Route::group(['middleware' => 'auth'], function () {
    Route::get('/', 'HomeController@index')->name('home');

    Route::get('/regions', 'RegionController@index');
    Route::post('/regions', 'RegionController@store');
    Route::put('/regions/{id}', 'RegionController@update');
    Route::delete('/regions/{id}', 'RegionController@delete');

    Route::get('/electricity', 'ElectricityController@index');
    Route::post('/electricity', 'ElectricityController@store');
    Route::put('/electricity/{id}', 'ElectricityController@update');
    Route::delete('/electricity/{id}', 'ElectricityController@delete');

});
