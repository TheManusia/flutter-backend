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

$router->group(['middleware' => 'cors'], function () use ($router) {
    $router->get('/', function () use ($router) {
        return $router->app->version();
    });

    $router->group(['prefix' => 'types'], function () use ($router) {
        $router->post('/datatables', ['uses' => 'MsTypeController@show']);
        $router->post('/', ['uses' => 'MsTypeController@store']);
        $router->get('/{id:[0-9]+}', ['uses' => 'MsTypeController@find']);
        $router->get('/select', ['uses' => 'MsTypeController@select']);
        $router->post('/update/{id}', ['uses' => 'MsTypeController@update']);
        $router->post('/delete/{id}', ['uses' => 'MsTypeController@delete']);
    });

    $router->group(['prefix' => 'products'], function () use ($router) {
        $router->post('/datatables', ['uses' => 'MsProductController@show']);
        $router->post('/', ['uses' => 'MsProductController@store']);
        $router->get('/{id:[0-9]+}', ['uses' => 'MsProductController@find']);
        $router->get('/select', ['uses' => 'MsProductController@select']);
        $router->post('/update/{id}', ['uses' => 'MsProductController@update']);
        $router->post('/delete/{id}', ['uses' => 'MsProductController@delete']);
    });
});
