<?php

use App\Http\Controllers\API\Auth\AuthenticateUser;
use App\Http\Controllers\API\Auth\LoginController;
use App\Http\Controllers\API\Auth\RegisterController;
use App\Http\Controllers\API\Presence\HitPresenceController;
use App\Http\Controllers\API\Presence\ListPresence;
use App\Http\Controllers\API\Presence\RegisterBiometricController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::post('register', RegisterController::class);
Route::post('login', LoginController::class);
Route::group(['middleware' => ['jwt.verify']],function(){
    Route::get('authenticate-user', AuthenticateUser::class);
    Route::post('register-biometric', RegisterBiometricController::class);
    Route::post('hit-presence', HitPresenceController::class);
    Route::post('list-presence', ListPresence::class);
});
