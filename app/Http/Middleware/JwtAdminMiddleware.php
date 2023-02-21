<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

class JwtAdminMiddleware
{

    public function handle(Request $request, Closure $next)
    {
        try {
            $token = $request->bearerToken();
            $adminToken = Auth::guard('admin')->setToken($token);

            if ($adminToken) {
                if (Auth::guard('admin')->check()) {
                    return $next($request);
                }
            }

            return response()->json([
                'date' => [
                    'message' => 'Token is Invalid',
                    'status_code' => ResponseAlias::HTTP_UNAUTHORIZED
                ]
            ], ResponseAlias::HTTP_UNAUTHORIZED);


        } catch (\Exception $e) {
            return response()->json([
                'data' => [
                    'message' => 'Token is Invalid',
                    'status_code' => ResponseAlias::HTTP_UNAUTHORIZED
                ]
            ], ResponseAlias::HTTP_UNAUTHORIZED);
        }
    }
}
