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

use App\Role;

Auth::routes(['register' => false]);

Route::group(['middleware' => 'auth'], function () {
    Route::get('/', function (){
        return redirect('/regions');
    })->name('home');

    Route::get('/regions', 'RegionController@index');

    Route::group(['middleware' => 'role:'. Role::ADMIN_ROLE_NAME], function () {
        
        Route::post('/regions', 'RegionController@store');
        Route::put('/regions/{id}', 'RegionController@update');
        Route::delete('/regions/{id}', 'RegionController@delete');
        
    });

    Route::get('/electricity', 'ElectricityController@index');
    Route::get('/electricity/create', 'ElectricityController@create');
    Route::post('/electricity', 'ElectricityController@store');
    Route::put('/electricity/{id}', 'ElectricityController@update');
    Route::delete('/electricity/{id}', 'ElectricityController@delete');

});
