<?php

namespace App\Http\Controllers;

class FallbackController extends Controller
{
    /**
     * Display all routes registered in system if application is launched in debug mode.
     * Othervice displays default 404 page
     */
    public function index()
    {
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
    }
}
