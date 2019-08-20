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

$router->get('/', function () use ($router) {
    return 'uno';
});

$router->post('validaciones', 'ValidacionController@store');
$router->post('auditorias', 'AuditoriaController@store');
// show, store, update, delete, all