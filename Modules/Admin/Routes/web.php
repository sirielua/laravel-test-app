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

use Illuminate\Support\Facades\Route;

Route::prefix('admin')->as('admin::')->group(function() {

    Route::middleware('admin.guest')->group(function() {

        Route::get('/login', 'Auth\LoginController@showLoginForm')->name('login');
        Route::post('/login', 'Auth\LoginController@login');

    });

    Route::middleware('admin.auth')->group(function() {

        Route::get('/', 'AdminController@index')->name('home');

        // Specific routes must be placed before resource routes,
        // otherwise they will be catched by resource routes
        Route::patch('/users/activate/{user}', 'UserController@activate')->name('users.activate');
        Route::patch('/users/deactivate/{user}', 'UserController@deactivate')->name('users.deactivate');
        Route::patch('/users/batch-update', 'UserController@batchUpdate')->name('users.batch-update');

        Route::patch('/contest-templates/activate/{contest_template}', 'ContestTemplateController@activate')->name('contest-templates.activate');
        Route::patch('/contest-templates/deactivate/{contest_template}', 'ContestTemplateController@deactivate')->name('contest-templates.deactivate');
        Route::patch('/contest-templates/batch-update', 'ContestTemplateController@batchUpdate')->name('contest-templates.batch-update');

        Route::patch('/participants/force-confirm/{participant}', 'ParticipantController@deactivate')->name('participants.force-confirm');
        Route::patch('/participants/batch-update', 'ParticipantController@batchUpdate')->name('participants.batch-update');

        Route::resources([
            'users' => 'UserController',
            'contest-templates' => 'ContestTemplateController',
        ]);

        Route::resource('participants', 'ParticipantController')->only([
            'index', 'show', 'destroy'
        ]);

    });

    Route::post('/logout', 'Auth\LoginController@logout')->name('logout');

});
