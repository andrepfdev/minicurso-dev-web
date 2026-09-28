<?php

use CodeIgniter\Router\RouteCollection;

/** @var RouteCollection $routes */
$routes->get('/', 'Home::index');

$routes->get('api/usuarios', 'Usuarios::index');
$routes->get('api/usuarios/(:num)', 'Usuarios::show/$1');
$routes->post('api/usuarios', 'Usuarios::create');
$routes->put('api/usuarios/(:num)', 'Usuarios::update/$1');
$routes->delete('api/usuarios/(:num)', 'Usuarios::delete/$1');
$routes->post('api/auth/login', 'Auth::login');
