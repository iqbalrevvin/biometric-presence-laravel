<?php

namespace App\Http\Controllers\API\Presence;

use App\Helpers\GlobalHelper;
use App\Http\Controllers\Controller;
use App\Models\Karyawan;
use App\Models\Presensi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Facades\JWTAuth;

class HitPresenceController extends Controller
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
            'tipe' => 'required|string',
        ]);

        if (!$validator->passes()) {
            return GlobalHelper::createResponse(false, 'Masalah dalam mengambil Biometrik ID Perangkat');
        }

        $user = JWTAuth::parseToken()->authenticate();
        $karyawan = Karyawan::where('user_id', $user->id)->first();
        $BiometricAvailable = Karyawan::where('biometric_id', $request->biometric_id)->exists();

        if ($BiometricAvailable) {
            if ($karyawan->biometric_id !== $request->biometric_id) {
                return GlobalHelper::createResponse(false, 'Mohon Maaf, kunci biometrik perangkat tidak di izinkan untuk akun ini, silahkan login dengan akun yang didaftarkan ke perangkat pertama kali, atau ajukan hapus kunci perangkat jika ingin mempairing/mendaftarkan ulang akun dan kunci perangkat!');
            }

            if ($karyawan->biometric_id == $request->biometric_id) {
                $presensi = new Presensi;
                $presensi->user_id = $karyawan->user_id;
                $presensi->tipe = $request->tipe;
                $presensi->latitude = $request->latitude;
                $presensi->longitude = $request->longitude;
                $presensi->tanggal = date('Y-m-d');
                $presensi->save();
                if ($presensi->save()) {
                    return GlobalHelper::createResponse(true, 'Terimakasih, Absen berhasil dikirim ke server');
                } else {
                    return GlobalHelper::createResponse(false, 'Masalah dalam menyimpan data, silahkan coba lagi!');
                }
            }
        } else {
            return GlobalHelper::createResponse(false, 'Mohon Maaf, kunci perangkat ini tidak terdaftar di server, silahkan login dengan akun anda serta lakukan kembali absen', 'BiometricKeyNull');
        }
        return GlobalHelper::createResponse(true, 'Kesalahan Tidak Diketahui');
    }
}
