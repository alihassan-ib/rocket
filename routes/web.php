<?php

/** @var Router $router */

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

use App\Models\LandingArea;
use App\Models\PlatformArea;
use Laravel\Lumen\Routing\Router;

$router->get('/', function () use ($router) {
    $area = new LandingArea(100, 100);
    $platform = new PlatformArea(5, 5, 10, 10, $area);
    $service = new \App\Services\RocketLandingService($platform);

    echo ($service->land(new \App\Models\Rocket(), 5,105));
    echo '</br>';
    echo ($service->land(new \App\Models\Rocket(), 5,5));
    echo '</br>';
    echo $service->land(new \App\Models\Rocket(), 5,5);
    echo '</br>';
    echo $service->land(new \App\Models\Rocket(), 6,6);
    echo '</br>';
    echo $service->land(new \App\Models\Rocket(), 16,15);
    echo '</br>';
    echo $service->land(new \App\Models\Rocket(), 7,7);
    echo '</br>';
    echo $service->land(new \App\Models\Rocket(), 8,7);
    echo '</br>';
});
