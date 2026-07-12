<?php

use CodeIgniter\Router\RouteCollection;

/** @var RouteCollection $routes */

// ====================================================================
// Public Routes (Bisa diakses tanpa login)
// ====================================================================
$routes->get('/', 'Home::index');
$routes->get('/login', 'AuthController::index');
$routes->post('/login', 'AuthController::login');
$routes->get('/logout', 'AuthController::logout');
$routes->get('/unauthorized', 'Home::unauthorized');
$routes->get('/migrate', 'Home::migrate');

// ====================================================================
// Protected Routes (Wajib Login menggunakan AuthFilter)
// ====================================================================
$routes->group('', ['filter' => 'auth'], static function ($routes) {

    // ----------------------------------------------------------------
    // Dashboard Routes (Berbeda per Role)
    // ----------------------------------------------------------------
    $routes->get('ukm/dashboard', 'DashboardController::adminDashboard', ['filter' => 'role:1']);
    $routes->get('kemahasiswaan/dashboard', 'DashboardController::kemahasiswaanDashboard', ['filter' => 'role:2']);
    $routes->get('wakilrektor3/dashboard', 'DashboardController::wakilRektor3Dashboard', ['filter' => 'role:3']);

    // ----------------------------------------------------------------
    // CRUD Routes (Bisa diakses user login, role logic ada di controller)
    // ----------------------------------------------------------------
    
    // Profile Management (Semua Role)
    $routes->get('profile', 'ProfileController::index');
    $routes->post('profile/update', 'ProfileController::update');
    $routes->get('profile/delete', 'ProfileController::delete');

    // User Management
    $routes->get('user', 'UserController::index');
    $routes->get('user/create', 'UserController::create');
    $routes->post('user/store', 'UserController::store');
    $routes->get('user/edit/(:num)', 'UserController::edit/$1');
    $routes->post('user/update/(:num)', 'UserController::update/$1');
    $routes->get('user/delete/(:num)', 'UserController::delete/$1');

    // UKM Management
    $routes->get('ukm', 'UKMController::index');
    $routes->get('ukm/create', 'UKMController::create');
    $routes->post('ukm/store', 'UKMController::store');
    $routes->get('ukm/edit/(:num)', 'UKMController::edit/$1');
    $routes->post('ukm/update/(:num)', 'UKMController::update/$1');
    $routes->get('ukm/delete/(:num)', 'UKMController::delete/$1');

    // Bagian UKM
    $routes->get('bagian-ukm', 'BagianUKMController::index');
    $routes->get('bagian-ukm/create', 'BagianUKMController::create');
    $routes->post('bagian-ukm/store', 'BagianUKMController::store');
    $routes->get('bagian-ukm/edit/(:num)', 'BagianUKMController::edit/$1');
    $routes->post('bagian-ukm/update/(:num)', 'BagianUKMController::update/$1');
    $routes->get('bagian-ukm/delete/(:num)', 'BagianUKMController::delete/$1');

    // Anggota UKM
    $routes->get('anggota-ukm', 'AnggotaUKMController::index');
    $routes->get('anggota-ukm/create', 'AnggotaUKMController::create');
    $routes->post('anggota-ukm/store', 'AnggotaUKMController::store');
    $routes->get('anggota-ukm/edit/(:num)', 'AnggotaUKMController::edit/$1');
    $routes->post('anggota-ukm/update/(:num)', 'AnggotaUKMController::update/$1');
    $routes->get('anggota-ukm/delete/(:num)', 'AnggotaUKMController::delete/$1');

    // Kegiatan
    $routes->get('kegiatan', 'KegiatanController::index');
    $routes->get('kegiatan/create', 'KegiatanController::create');
    $routes->post('kegiatan/store', 'KegiatanController::store');
    $routes->get('kegiatan/show/(:num)', 'KegiatanController::show/$1');
    $routes->get('kegiatan/edit/(:num)', 'KegiatanController::edit/$1');
    $routes->post('kegiatan/update/(:num)', 'KegiatanController::update/$1');
    $routes->get('kegiatan/delete/(:num)', 'KegiatanController::delete/$1');

    // Proposal
    $routes->get('proposal', 'ProposalController::index');
    $routes->get('proposal/create', 'ProposalController::create');
    $routes->post('proposal/store', 'ProposalController::store');
    $routes->get('proposal/show/(:num)', 'ProposalController::show/$1');
    $routes->get('proposal/edit/(:num)', 'ProposalController::edit/$1');
    $routes->post('proposal/update/(:num)', 'ProposalController::update/$1');
    $routes->get('proposal/delete/(:num)', 'ProposalController::delete/$1');
    $routes->post('proposal/approve/(:num)', 'ProposalController::approve/$1');
    $routes->post('proposal/revisi/(:num)', 'ProposalController::revisi/$1');
    $routes->post('proposal/tolak/(:num)', 'ProposalController::tolak/$1');

    // LPJ
    $routes->get('lpj', 'LPJKegiatanController::index');
    $routes->get('lpj/create', 'LPJKegiatanController::create');
    $routes->post('lpj/store', 'LPJKegiatanController::store');
    $routes->get('lpj/show/(:num)', 'LPJKegiatanController::show/$1');
    $routes->get('lpj/edit/(:num)', 'LPJKegiatanController::edit/$1');
    $routes->post('lpj/update/(:num)', 'LPJKegiatanController::update/$1');
    $routes->get('lpj/delete/(:num)', 'LPJKegiatanController::delete/$1');
    $routes->post('lpj/approve/(:num)', 'LPJKegiatanController::approve/$1');
    $routes->post('lpj/revisi/(:num)', 'LPJKegiatanController::revisi/$1');
    $routes->post('lpj/tolak/(:num)', 'LPJKegiatanController::tolak/$1');

});
