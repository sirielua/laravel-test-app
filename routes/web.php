<?php

use Illuminate\Support\Facades\Route;

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
})->name('index');

Route::get('/home', 'HomeController@index')->name('home');

// @see Laravel\Ui\AuthRouteMethods
Auth::routes([
    'register' => false,
    'reset' => false,
    'confirm' => false,
    'verify' => false,
]);

Route::fallback(function () {
    if(env('APP_DEBUG') === true) {
        $allRoutes = [];
        
        foreach (app('routes')->getRoutes() as $i => $route)
        {
            $action = $route->getAction();
            
            if (array_key_exists('controller', $action))
            {
                // You can also use explode('@', $action['controller']); here
                // to separate the class name from the method
                $allRoutes[$i]['controller'] = $action['controller'];
            }
            
            if($name = $route->getName()) {
                $allRoutes[$i]['name'] = $name;
            }
        }

        echo '<pre>'; print_r($allRoutes); echo '</pre>';
    }
    abort(404);
});
