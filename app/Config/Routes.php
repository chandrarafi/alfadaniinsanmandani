<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');
$routes->get('paket/(:segment)', 'Home::detailPaket/$1');

// Authentication Routes
$routes->get('auth', 'Auth::index');
$routes->get('auth/register', 'Auth::register');
$routes->post('auth/processLogin', 'Auth::processLogin');
$routes->post('auth/processRegister', 'Auth::processRegister');
$routes->get('auth/verify/(:any)', 'Auth::verify/$1');
$routes->get('auth/logout', 'Auth::logout');

// Admin routes
$routes->group('admin', ['namespace' => 'App\Controllers\Admin'], function ($routes) {
    $routes->get('/', 'Admin::index');

    // Paket routes
    $routes->group('paket', function ($routes) {
        $routes->get('/', 'Paket::index');
        $routes->get('add', 'Paket::add');
        $routes->get('edit/(:segment)', 'Paket::edit/$1');
        $routes->get('getAll', 'Paket::getAll');
        $routes->post('getById', 'Paket::getById');
        $routes->post('save', 'Paket::save');
        $routes->post('update', 'Paket::update');
        $routes->post('changeStatus', 'Paket::changeStatus');
        $routes->post('delete', 'Paket::delete');
    });
});

// Admin routes (root namespace)
$routes->group('admin', ['filter' => 'auth:admin'], function ($routes) {
    $routes->get('check-expired-pendaftaran', 'Admin::checkExpiredPendaftaran');
});

// Kategori Routes
$routes->group('admin/kategori', ['filter' => 'auth:admin'], function ($routes) {
    $routes->get('/', 'Admin\Kategori::index');
    $routes->get('getAll', 'Admin\Kategori::getAll');
    $routes->post('save', 'Admin\Kategori::save');
    $routes->post('update', 'Admin\Kategori::update');
    $routes->post('changeStatus', 'Admin\Kategori::changeStatus');
    $routes->post('delete', 'Admin\Kategori::delete');
});

// User Routes
$routes->group('admin/user', ['filter' => 'auth:admin'], function ($routes) {
    $routes->get('/', 'Admin\User::index');
    $routes->get('getAll', 'Admin\User::getAll');
    $routes->post('save', 'Admin\User::save');
    $routes->post('update', 'Admin\User::update');
    $routes->post('changeStatus', 'Admin\User::changeStatus');
    $routes->post('delete', 'Admin\User::delete');
});

// Paket Routes
$routes->group('admin/paket', ['filter' => 'auth:admin'], function ($routes) {
    $routes->get('/', 'Admin\Paket::index');
    $routes->get('getAll', 'Admin\Paket::getAll');
    $routes->get('add', 'Admin\Paket::add');
    $routes->get('edit/(:any)', 'Admin\Paket::edit/$1');
    $routes->post('save', 'Admin\Paket::save');
    $routes->post('update/(:any)', 'Admin\Paket::update/$1');
    $routes->post('changeStatus', 'Admin\Paket::changeStatus');
    $routes->post('delete', 'Admin\Paket::delete');
});

// Jamaah Routes (Admin)
$routes->group('admin/jamaah', ['filter' => 'auth:admin'], function ($routes) {
    $routes->get('/', 'Admin\Jamaah::index');
    $routes->get('getAll', 'Admin\Jamaah::getAll');
    $routes->get('add', 'Admin\Jamaah::add');
    $routes->get('edit/(:any)', 'Admin\Jamaah::edit/$1');
    $routes->post('save', 'Admin\Jamaah::save');
    $routes->post('update/(:any)', 'Admin\Jamaah::update/$1');
    $routes->post('changeStatus', 'Admin\Jamaah::changeStatus');
    $routes->post('delete', 'Admin\Jamaah::delete');
});

// Laporan routes
$routes->group('admin/laporan', ['filter' => 'auth:admin'], function ($routes) {
    $routes->get('jamaah', 'Admin\Laporan::jamaah');
    $routes->get('jamaah/cetak', 'Admin\Laporan::cetakJamaahPDF');
    $routes->get('paket', 'Admin\Laporan::paket');
    $routes->get('paket/data', 'Admin\Laporan::getPaketData');
    $routes->get('paket/pdf', 'Admin\Laporan::exportPaketPDF');

    // Laporan Pendaftaran
    $routes->get('pendaftaran', 'Admin\Laporan::pendaftaran');
    $routes->post('pendaftaran/harian', 'Admin\Laporan::pendaftaranHarian');
    $routes->post('pendaftaran/bulanan', 'Admin\Laporan::pendaftaranBulanan');
    $routes->post('pendaftaran/tahunan', 'Admin\Laporan::pendaftaranTahunan');

    // Laporan Pembayaran
    $routes->get('pembayaran', 'Admin\Laporan::pembayaran');
    $routes->post('pembayaran/harian', 'Admin\Laporan::pembayaranHarian');
    $routes->post('pembayaran/bulanan', 'Admin\Laporan::pembayaranBulanan');
    $routes->post('pembayaran/tahunan', 'Admin\Laporan::pembayaranTahunan');
});

// Admin Routes
$routes->group('admin', ['filter' => 'auth:admin'], function ($routes) {
    $routes->get('/', 'Admin\Admin::index');

    // Pendaftaran dan Pembayaran routes
    $routes->get('pendaftaran', 'Admin\Admin::pendaftaran');
    $routes->get('get-pendaftaran', 'Admin\Admin::getPendaftaran');
    $routes->get('pendaftaran/detail/(:segment)', 'Admin\Admin::detailPendaftaran/$1');
    $routes->post('konfirmasi-pembayaran', 'Admin\Admin::konfirmasiPembayaran');

    // Pendaftaran Langsung routes
    $routes->get('pendaftaran/langsung', 'Admin\Admin::pendaftaranLangsung');
    $routes->get('cariJamaah', 'Admin\Admin::cariJamaah');
    $routes->post('tambahJamaah', 'Admin\Admin::tambahJamaah');
    $routes->post('prosesPendaftaranLangsung', 'Admin\Admin::prosesPendaftaranLangsung');
    $routes->post('prosesPembayaranLangsung', 'Admin\Admin::prosesPembayaranLangsung');

    // Tambahkan rute admin lainnya di sini
    // ...
});

// Jamaah Routes
$routes->group('jamaah', ['filter' => 'auth'], function ($routes) {
    $routes->get('/', 'Jamaah::index');
    $routes->get('profile', 'Jamaah::profile');
    $routes->post('update-profile', 'Jamaah::updateProfile');
    $routes->post('change-password', 'Jamaah::changePassword');

    // Orders routes
    $routes->get('orders', 'Jamaah::orders');
    $routes->get('orders/new', 'Jamaah::newOrder');
    $routes->get('get-pendaftaran', 'Jamaah::getPendaftaran');
    $routes->get('orders/pembayaran/(:segment)', 'Jamaah::pembayaran/$1');
    $routes->get('orders/detail/(:segment)', 'Jamaah::detailOrder/$1');

    // Paket routes
    $routes->get('paket', 'Jamaah::paket');
    $routes->get('paket/(:segment)', 'Jamaah::paketDetail/$1');

    // Pendaftaran routes
    $routes->get('daftar/(:segment)', 'Jamaah::daftar/$1');
    $routes->post('save-pendaftaran', 'Jamaah::savePendaftaran');
    $routes->post('tambah-jamaah', 'Jamaah::tambahJamaah');
    $routes->get('check-pending-pendaftaran', 'Jamaah::checkPendingPendaftaran');

    // Dokumen routes
    $routes->get('dokumen', 'Jamaah::dokumen');
    $routes->post('upload-dokumen', 'Jamaah::uploadDokumen');
    $routes->post('uploadDokumen', 'Jamaah::uploadDokumen');
    $routes->get('get-dokumen/(:segment)', 'Jamaah::getDokumen/$1');
    $routes->get('getDokumen/(:segment)', 'Jamaah::getDokumen/$1');
    $routes->post('hapusDokumen/(:segment)', 'Jamaah::hapusDokumen/$1');

    // Pembayaran routes
    $routes->get('pembayaran/(:segment)', 'Jamaah::pembayaran/$1');
    $routes->post('save-pembayaran', 'Jamaah::savePembayaran');
    $routes->post('update-pendaftaran-status/(:segment)', 'Jamaah::updatePendaftaranStatus/$1');
    $routes->get('faktur/(:segment)', 'Jamaah::fakturPembayaran/$1');
    $routes->get('cetakFaktur/(:segment)', 'Jamaah::cetakFaktur/$1');

    // Jamaah Referensi routes
    $routes->get('referensi', 'Jamaah::jamaahReferensi');
    $routes->get('get-jamaah-referensi', 'Jamaah::getJamaahReferensi');
    $routes->post('tambah-jamaah-referensi', 'Jamaah::tambahJamaahReferensi');
});

// Pimpinan Routes
$routes->get('pimpinan', 'Pimpinan::index', ['filter' => 'auth:pimpinan']);

// Cron job routes
$routes->get('cron/check-expired-pendaftaran', 'Jamaah::checkExpiredPendaftaran');
