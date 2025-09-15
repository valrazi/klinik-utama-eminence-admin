<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 * 
 */

$routes->post('login', 'ClientApi::login');

$routes->get('auth/login-backoffice', 'Auth::indexBackoffice');
$routes->get('backoffice', 'Backoffice::index');
$routes->get('backoffice/tambah-staff', 'Backoffice::addStaff');
$routes->post('backoffice/tambah-staff', 'Backoffice::saveStaff');

// Doctors
$routes->get('backoffice/doctors', 'Doctors::index');
$routes->get('backoffice/doctors/jadwal/(:num)', 'Doctors::schedule/$1');

// add new schedule (form + submit)
$routes->get('backoffice/doctors/schedule/(:num)/create', 'Doctors::createSchedule/$1');
$routes->post('backoffice/doctors/schedule/(:num)/store', 'Doctors::storeSchedule/$1');


// update doctor (POST)
$routes->get('backoffice/doctors/edit/(:num)', 'Doctors::edit/$1');
$routes->post('backoffice/doctors/update/(:num)', 'Doctors::update/$1');

// delete
$routes->post('backoffice/doctors/deactivate/(:num)', 'Doctors::deactivate/$1');

//activate
$routes->post('backoffice/doctors/activate/(:num)', 'Doctors::activate/$1');


// Terapis
$routes->get('backoffice/therapists', 'Therapists::index');
// update staff (POST)
$routes->get('backoffice/therapists/edit/(:num)', 'Therapists::edit/$1');
$routes->post('backoffice/therapists/update/(:num)', 'Therapists::update/$1');

// delete
$routes->post('backoffice/therapists/deactivate/(:num)', 'Therapists::deactivate/$1');

//activate
$routes->post('backoffice/therapists/activate/(:num)', 'Therapists::activate/$1');

$routes->group('backoffice', ['namespace' => 'App\Controllers'], function($routes) {
    $routes->get('reservations', 'ReservationsController::index');
    $routes->get('reservations/(:num)/reschedule', 'ReservationsController::reschedule/$1'); // show form
    $routes->post('reservations/(:num)/get-schedules', 'ReservationsController::getSchedules/$1'); // fetch available schedules by date
    $routes->post('reservations/(:num)/update-reschedule', 'ReservationsController::updateReschedule/$1'); // save new schedule
});



