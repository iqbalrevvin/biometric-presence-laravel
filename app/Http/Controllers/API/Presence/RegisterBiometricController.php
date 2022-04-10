<?php

namespace App\Http\Controllers\API\Presence;

use App\Helpers\GlobalHelper;
use App\Http\Controllers\Controller;
use App\Models\Karyawan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Facades\JWTAuth;

class RegisterBiometricController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'biometric_id' => 'required',
        ]);
        if (!$validator->passes()) {
            return GlobalHelper::createResponse(false, 'Masalah dalam mendaftarkan Biometrik ID');
        }
        $user = JWTAuth::parseToken()->authenticate();
        $karyawan = Karyawan::where('user_id', $user->id);
        if($karyawan){
            if (!$karyawan->first()->biometric_id) {
                $karyawan->update(['biometric_id' => $request->biometric_id]);
                return GlobalHelper::createResponse(true, 'ID Biometrik Perangkat dengan akun ini berhasil di daftarkan ke server, perangkat ini hanya bisa digunakan untuk presensi dengan akun ' . $karyawan->first()->email);
            } else {
                return GlobalHelper::createResponse(false, 'Akun yang anda loginkan sudah terdaftar di perangkat lain!');
            }
        }
        return GlobalHelper::createResponse(false, 'Masalah dalam mendaftarkan Biometrik ID');
    }
}
