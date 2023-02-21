<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\AdminLoginRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AdminAuthController extends Controller
{
    public function login(AdminLoginRequest $request)
    {
        $credentials = request(['email', 'password']);

        if (!$token = auth()->guard('admin')->attempt($credentials)) {
            return response()->json([
                'data' => [
                    'error' => 'Unauthorized'
                ],
                'status_code' => 401
            ], 401);
        }

        return response()->json([
            'data' => [
                'access_token' => $token,
                'token_type' => 'bearer',
                'user_info' => auth()->guard('admin')->user(),
                'type' => 1,
                'expires_in' => auth()->guard('admin')->factory()->getTTL() * 60
            ],
            'status_code' => 200
        ]);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function refresh(Request $request): JsonResponse
    {
        return  response()->json([
            'data' => [
                'access_token' => auth('admin')->refresh(),
                'token_type' => 'bearer',
                'expires_in' => auth()->guard('admin')->factory()->getTTL() * 60
            ],
            'status_code' => 200
        ]);
    }

    /**
     * @return JsonResponse
     */
    public function logout(): JsonResponse
    {
        auth('admin')->logout();

        return response()->json(['message' => 'Successfully logged out']);
    }


}
