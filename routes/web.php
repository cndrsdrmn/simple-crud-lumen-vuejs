<?php

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

$router->get('/', 'ExampleController@index');
$router->get('/examples', 'ExampleController@fetch');
$router->get('/example/{id}', 'ExampleController@edit');
$router->put('/example/{id}', 'ExampleController@update');
$router->post('/example', 'ExampleController@store');
$router->delete('/example/{id}', 'ExampleController@destroy');
