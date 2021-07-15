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

    $router->group(['namespace' => 'Masters'], function () use ($router) {
        $router->group(['prefix' => 'types'], function () use ($router) {
            $router->post('/datatables', ['uses' => 'TypeController@show']);
            $router->post('/', ['uses' => 'TypeController@store']);
            $router->get('/{id:[0-9]+}', ['uses' => 'TypeController@find']);
            $router->get('/select', ['uses' => 'TypeController@select']);
            $router->post('/update/{id}', ['uses' => 'TypeController@update']);
            $router->post('/delete/{id}', ['uses' => 'TypeController@delete']);
        });

        $router->group(['prefix' => 'product'], function () use ($router) {
            $router->post('/datatables', ['uses' => 'ProductController@show']);
            $router->post('/', ['uses' => 'ProductController@store']);
            $router->get('/{id:[0-9]+}', ['uses' => 'ProductController@find']);
            $router->post('/update/{id}', ['uses' => 'ProductController@update']);
            $router->post('/delete/{id}', ['uses' => 'ProductController@delete']);
        });
    });
});
