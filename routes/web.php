<?php

use Illuminate\Support\Facades\Route;

//use Illuminate\Support\Facades\Auth;

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

//Route::get('/', 'IndexController@index')->name('index');
//Route::get('/home', 'HomeController@index')->name('home');

// @see Laravel\Ui\AuthRouteMethods
Auth::routes([
    'register' => false,
    'reset' => false,
    'confirm' => false,
    'verify' => false,
]);

Route::get('/', 'ParticipantController@index')->name('index');
Route::post('/register', 'ParticipantController@register')->name('participants.register');
Route::get('/verify', 'ParticipantController@verify')->name('participants.verify');
Route::patch('/resend-verification', 'ParticipantController@resendVerification')->name('participants.resend-verification');
Route::patch('/edit-number', 'ParticipantController@resendVerification')->name('participants.edit-number');
Route::patch('/confirm', 'ParticipantController@confirm')->name('participants.confirm');
Route::get('/share', 'ParticipantController@share')->name('participants.share');
Route::get('/messenger', 'ParticipantController@messenger')->name('participants.messenger');
Route::get('/user/{user}', 'ParticipantController@user')->name('participants.user');

Route::fallback('FallbackController@index');
