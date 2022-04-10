<?php

// --------------------------
// Custom Backpack Routes
// --------------------------
// This route file is loaded automatically by Backpack\Base.
// Routes you generate using Backpack\Generators will be placed here.

use Illuminate\Support\Facades\Route;

Route::group([
    'prefix'     => config('backpack.base.route_prefix', 'admin'),
    'middleware' => array_merge(
        (array) config('backpack.base.web_middleware', 'web'),
        ['web', backpack_middleware(), 'role:Developer|Admin']
    ),
    'namespace'  => 'App\Http\Controllers\Admin',
], function () { // custom admin routes
    Route::get('tester', 'TesterController@index');
    Route::crud('divisi', 'DivisiCrudController');
    Route::crud('jam_kerja', 'Jam_kerjaCrudController');
    Route::crud('karyawan', 'KaryawanCrudController');
}); // this should be the absolute last line of this file

Route::group([
    'prefix' => config('backpack.base.route_prefix', 'admin'),
    'middleware' => array_merge(
        (array) config('backpack.base.web_middleware', 'web'),
        ['web', backpack_middleware()]
    ),
    'namespace' => 'App\Http\Controllers\Admin'
], function(){
    Route::crud('presensi', 'PresensiCrudController');
});