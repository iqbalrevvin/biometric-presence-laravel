<?php

namespace App\Http\Controllers\API\Auth;

use App\Helpers\GlobalHelper;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
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
            'name' => ['required', 'min:3'],
            'email' => ['email', 'required', 'unique:users,email'],
            'password' => ['required', 'min:6'],
            'role' => ['required']
        ]);

        if($validator->passes()){
            $user = new User;
            $user->name = $request->name;
            $user->email = $request->email;
            $user->password = Hash::make($request->password);
            $user->save();

            if($user->save()){
                $user->assignRole($request->role);
                return GlobalHelper::createResponse(true, 'Terimakasih, Pendaftaran berhasil', $user);
            }else{
                return GLobalHelper::createResponse(false, 'Mohon Maaf, Terjadi kesalahan', $user);
            }
            
        }else{
            return GlobalHelper::createResponse(false, 'Mohon maaf, pendaftaran gagal, silahkan periksa kembali inputan', $validator->errors()->all());
        }
    }
}
