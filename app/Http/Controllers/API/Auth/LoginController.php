<?php

namespace App\Http\Controllers\API\Auth;

use App\Helpers\GlobalHelper;
use App\Http\Controllers\Controller;
use App\Models\Karyawan;
use App\Models\User;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use Validator;

class LoginController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    
    public function __invoke(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'email' => ['required', 'email', 'exists:users,email'],
            'password' => ['required']
        ]);
        if($validator->passes()){
            $token = JWTAuth::attempt($request->only('email', 'password'));

            if($token){
                $user = User::where('email', $request->email)->first();
                $karyawan = Karyawan::with('divisi')->with('jam_kerja')->where('user_id', $user->id)->first();
                if(!$karyawan){
                    return GlobalHelper::createResponse(false, 'Mohon maaf, akun anda belum terdaftar sebagai karyawan');
                }
                $user->token = $token;
                $user->profile = $karyawan;
                return GlobalHelper::createResponse(true, 'Login berhasil', $user);
            }
            if(!$token){
                return GlobalHelper::createResponse(false, 'Login gagal atau akun tidak terkait dengan karyawan!');
            }
        }else{
            return GlobalHelper::createResponse(false, 'Mohon maaf, login gagal, silahkan periksa kembali inputan', $validator->errors()->all());
        }
        
    }
}
