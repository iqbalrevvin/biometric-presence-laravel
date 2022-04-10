<?php

namespace App\Http\Controllers\API\Auth;

use App\Helpers\GlobalHelper;
use App\Http\Controllers\Controller;
use App\Models\Karyawan;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthenticateUser extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request)
    {
        try {
            if (!$user = JWTAuth::parseToken()->authenticate()) {
                return response()->json(['user_not_found'], 404);
            } else {
                $karyawanData = Karyawan::where('user_id', $user->id)->first();
                $userData = [
                    'user' => $user,
                    'detail' => [
                        'nama_lengkap' => $karyawanData->nama_lengkap,
                        'divisi' => $karyawanData->divisi->nama,
                        'jam_kerja' => $karyawanData->jam_kerja->nama,
                    ]
                ];
            }
        } catch (TokenExpiredException $e) {
            return response(401)->json(['token_expired'], $e->getStatusCode());
        } catch (TokenInvalidException $e) {
            return response(401)->json(['token_invalid'], $e->getStatusCode());
        } catch (JWTException $e) {
            return response()->json(['token_absent'], $e->getStatusCode());
        }

        // return response()->json(compact('user'));
        return GlobalHelper::createResponse(true, 'Get user auth success', $userData);
    }
}
