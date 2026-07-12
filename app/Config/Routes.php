<?php

use CodeIgniter\Router\RouteCollection;

/** @var RouteCollection $routes */

// ====================================================================
// Public Routes (Bisa diakses tanpa login)
// ====================================================================
$routes->get('/', 'Home::index');
$routes->get('/login', 'AuthController::login');
$routes->post('/login', 'AuthController::processLogin');
$routes->get('/logout', 'AuthController::logout');
$routes->get('/unauthorized', 'Home::unauthorized');

// ====================================================================
// Protected Routes (Wajib Login menggunakan AuthFilter)
// ====================================================================
$routes->group('', ['filter' => 'auth'], static function ($routes) {

    // ----------------------------------------------------------------
    // Admin UKM Routes (Role: 1)
    // ----------------------------------------------------------------
    $routes->group('ukm', ['filter' => 'role:1'], static function ($routes) {
        $routes->get('/', 'AdminUkmController::dashboard');
        // Tambahkan rute spesifik Admin UKM di sini
        // $routes->get('kegiatan', 'AdminUkmController::kegiatan');
    });

    // ----------------------------------------------------------------
    // Admin Kemahasiswaan Routes (Role: 2)
    // ----------------------------------------------------------------
    $routes->group('kemahasiswaan', ['filter' => 'role:2'], static function ($routes) {
        $routes->get('/', 'AdminKemahasiswaanController::dashboard');
        // Tambahkan rute spesifik Admin Kemahasiswaan di sini
    });

    // ----------------------------------------------------------------
    // Wakil Rektor III Routes (Role: 3)
    // ----------------------------------------------------------------
    $routes->group('wakilrektor3', ['filter' => 'role:3'], static function ($routes) {
        $routes->get('/', 'WakilRektorController::dashboard');
        // Tambahkan rute spesifik Wakil Rektor III di sini
    });

});
