<?php

namespace App\Http\Controllers\API\Presence;

use App\Helpers\GlobalHelper;
use App\Http\Controllers\Controller;
use App\Models\Presensi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Facades\JWTAuth as FacadesJWTAuth;

class ListPresence extends Controller
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
            'offset' => 'required'
        ]);
        if (!$validator->passes()) {
            return GlobalHelper::createResponse(false, 'Offset harus di isi!');
        }
        $user = FacadesJWTAuth::parseToken()->authenticate();
        $list = Presensi::where('user_id', $user->id)->limit(5)->offset($request->offset)->orderBy('created_at', 'DESC')->get();
        return GlobalHelper::createResponse(true, 'Data ditemukan', $list);
    }
}
