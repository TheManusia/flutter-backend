<?php

/** @var \Laravel\Lumen\Routing\Router $router */

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

$router->get('/', function () use ($router) {
    return $router->app->version();
});

$router->post('types/datatables', 'MsTypeController@show');
$router->post('types', 'MsTypeController@store');
$router->get('types[/{id:[0-9]+}]', 'MsTypeController@find');
$router->get('types/select', 'MsTypeController@select');
$router->post('types/update/{id}', 'MsTypeController@update');
