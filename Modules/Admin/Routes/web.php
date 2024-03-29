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

Route::prefix('admin')->as('admin::')->group(function() {

    Route::middleware('admin.guest')->group(function() {

        Route::get('/login', 'Auth\LoginController@showLoginForm')->name('login');
        Route::post('/login', 'Auth\LoginController@login');

    });

    Route::middleware('admin.auth')->group(function() {

        Route::get('/', 'AdminController@index')->name('home');

        // Specific routes must be placed before resource routes,
        // otherwise they will be catched by resource routes
        Route::patch('/users/batch-update', 'UserController@batchUpdate')->name('users.batch-update');
        Route::patch('/users/activate/{user}', 'UserController@activate')->name('users.activate');
        Route::patch('/users/deactivate/{user}', 'UserController@deactivate')->name('users.deactivate');

        Route::resources([
            'users' => 'UserController',
        ]);

    });

    Route::post('/logout', 'Auth\LoginController@logout')->name('logout');

});
