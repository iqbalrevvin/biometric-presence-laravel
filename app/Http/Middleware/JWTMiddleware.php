<?php

namespace App\Http\Middleware;

use App\Helpers\GlobalHelper;
use Closure;
use Illuminate\Http\Request;
use \Tymon\JWTAuth\Exceptions\TokenInvalidException;
use \Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Exception;
use JWTAuth;
use Tymon\JWTAuth\Http\Middleware\BaseMiddleware;

class JWTMiddleware extends BaseMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        try {
            $user = JWTAuth::parseToken()->authenticate();
        } catch (Exception $e) {
            if ($e instanceof TokenInvalidException){
                return GlobalHelper::createResponse(false, 'Token Autentikasi tidak valid, silahkan logout dan login ulang!');
            }else if ($e instanceof TokenExpiredException){
                return GlobalHelper::createResponse(false, 'Masa Aktif Token Autentikan Habis, Silahkan logout & login ulang!');
            }else{
                return GlobalHelper::createResponse(false, 'Otorisasi token tidak ditemukan, pastikan login dengan benar!');
            }
        }
        return $next($request);
    }
}
