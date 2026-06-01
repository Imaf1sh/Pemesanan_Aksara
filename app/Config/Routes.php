<?php

use CodeIgniter\Router\RouteCollection;

/** @var RouteCollection $routes */
$routes->get('/', '\App\Modules\Home\Controllers\Home::index');
$routes->get('/pos', '\App\Modules\POS\Controllers\Pos::index');
$routes->get('/kds', '\App\Modules\KDS\Controllers\Kds::index');

// Auth Routes
$routes->get('login', '\App\Modules\Auth\Controllers\Auth::login');
$routes->post('login', '\App\Modules\Auth\Controllers\Auth::attemptLogin');
$routes->get('logout', '\App\Modules\Auth\Controllers\Auth::logout');

// Attendance Routes
$routes->post('attendance/submit', '\App\Modules\Attendance\Controllers\Attendance::submit');
$routes->get('attendance/history', '\App\Modules\Attendance\Controllers\Attendance::history');

// Route api and api.php to Api Controller for seamless migration
$routes->add('api', '\App\Modules\POS\Controllers\Api::index');
$routes->add('api.php', '\App\Modules\POS\Controllers\Api::index');

// Route old vanilla filenames for complete backward compatibility
$routes->add('kasir.php', '\App\Modules\KDS\Controllers\Kds::index');
$routes->add('pos.php', '\App\Modules\POS\Controllers\Pos::index');
$routes->add('index.php', '\App\Modules\Home\Controllers\Home::index');
