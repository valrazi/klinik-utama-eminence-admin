<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 * 
 */

$routes->get('/', function () {
    return redirect()->to('/backoffice');
});

$routes->get('auth/login', 'Auth::indexBackoffice');

$routes->post('auth/login', 'Auth::login');
// 🔑 Sign out
$routes->get('auth/logout', 'Auth::logout');


$routes->group('backoffice', [
    'namespace' => 'App\Controllers',
    'filter'    => 'auth'   // ✅ protect everything inside
], function ($routes) {
    $routes->get('/', 'Backoffice::index');
    $routes->get('tambah-staff', 'Backoffice::addStaff');
    $routes->post('tambah-staff', 'Backoffice::saveStaff');

    // Doctors
    $routes->get('doctors', 'Doctors::index');
    $routes->get('doctors/jadwal/(:num)', 'Doctors::schedule/$1');
    $routes->get('doctors/schedule/(:num)/create', 'Doctors::createSchedule/$1');
    $routes->post('doctors/schedule/(:num)/store', 'Doctors::storeSchedule/$1');
    $routes->get('doctors/edit/(:num)', 'Doctors::edit/$1');
    $routes->post('doctors/update/(:num)', 'Doctors::update/$1');
    $routes->post('doctors/deactivate/(:num)', 'Doctors::deactivate/$1');
    $routes->post('doctors/activate/(:num)', 'Doctors::activate/$1');

    // Therapists
    $routes->get('therapists', 'Therapists::index');
    $routes->get('therapists/edit/(:num)', 'Therapists::edit/$1');
    $routes->post('therapists/update/(:num)', 'Therapists::update/$1');
    $routes->post('therapists/deactivate/(:num)', 'Therapists::deactivate/$1');
    $routes->post('therapists/activate/(:num)', 'Therapists::activate/$1');

    // Reservations
    $routes->get('reservations', 'ReservationsController::index');
    $routes->get('reservations/(:num)/reschedule', 'ReservationsController::reschedule/$1');
    $routes->post('reservations/(:num)/get-schedules', 'ReservationsController::getSchedules/$1');
    $routes->post('reservations/(:num)/update-reschedule', 'ReservationsController::updateReschedule/$1');
});
