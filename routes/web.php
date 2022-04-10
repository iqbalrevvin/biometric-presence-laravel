<?php

use App\Http\Controllers\TesterController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});


Route::group(['middleware' => ['web', 'admin'], 'prefix' => config('backpack.base.route_prefix', 'admin')], function () {
    Route::get('tester', [TesterController::class, 'index']);
  });