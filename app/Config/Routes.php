<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes -> match(['GET', 'POST'], '/', 'Api::index');
$routes -> post('/create', 'Api::create');
$routes -> match(['GET', 'POST'], '/read/(:segment)', 'Api::read/$1');
$routes -> match(['PUT', 'POST'], '/update/(:segment)', 'Api::update/$1');
$routes -> get('/delete/(:segment)', 'Api::delete/$1');
// $routes -> resource('api',['controller' => 'Api']);
