<?php

use Illuminate\Support\Facades\Route;

use Illuminate\Support\Facades\Auth;
use App\Http\Middleware\CheckParticipantRegistrationState;
use App\Http\Middleware\FacebookWebhookMiddleware;

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

// Participant
Route::get('/user/{user}', 'ParticipantController@user')->name('participants.user');
Route::get('/user-tracking/{user}', 'ParticipantController@referral')->name('participants.referral');

Route::middleware([CheckParticipantRegistrationState::class])->group(function() {
    Route::get('/', 'ParticipantController@landing')->name('index');
    Route::post('/register', 'ParticipantController@register')->name('participants.register');
    Route::get('/verify', 'ParticipantController@verify')->name('participants.verify');
    Route::patch('/resend-verification', 'ParticipantController@resendVerification')->name('participants.resend-verification');
    Route::patch('/edit-number', 'ParticipantController@editNumber')->name('participants.edit-number');
    Route::patch('/confirm', 'ParticipantController@confirmWithCode')->name('participants.confirm-code')->middleware('throttle:10,1');
    Route::get('/share', 'ParticipantController@share')->name('participants.share');
    Route::get('/messenger', 'ParticipantController@messenger')->name('participants.messenger');
});

Route::get('/confirm/{id}/code/{code}', 'ParticipantController@confirmWithLink')->name('participants.confirm-link')->middleware('throttle:10,1');
Route::delete('/reset', 'ParticipantController@registerAgain')->name('participants.reset');

// Facebook
Route::get('/messenger-webhook', 'FacebookWebhook@verify')->middleware([FacebookWebhookMiddleware::class]);
Route::post('/messenger-webhook', 'FacebookWebhook@index')->middleware([FacebookWebhookMiddleware::class]);

// Fallback
Route::fallback('FallbackController@index');
