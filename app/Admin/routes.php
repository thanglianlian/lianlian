<?php

use Illuminate\Routing\Router;

Admin::routes();

Route::group([
    'prefix'        => config('admin.route.prefix'),
    'namespace'     => config('admin.route.namespace'),
    'middleware'    => config('admin.route.middleware'),
    'as'            => config('admin.route.prefix') . '.',
], function (Router $router) {

    $router->get('/', 'HomeController@index')->name('home');


    $router->get('/customers/{id}/data', 'CustomerController@data');
    $router->get('/customers/{id}/chart', 'CustomerController@dataChart');

    $router->get('/customers/{id}/ajaxChart', 'CustomerController@ajaxChart');

    $router->post('/customers/{id}/importData', 'CustomerController@importData');
    $router->resource('customers', CustomerController::class);
    //$router->get('/customers/data', 'CustomerController@data');

});
