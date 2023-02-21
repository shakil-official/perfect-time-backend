<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserLoginRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UserAuthController extends Controller
{
    public function login(UserLoginRequest $request)
    {
        $credentials = request(['email', 'password']);

        if (!$token = auth()->guard('web')->attempt($credentials)) {
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
                'user_info' => auth()->guard('web')->user(),
                'type' => 2,
                'expires_in' => auth()->guard('web')->factory()->getTTL() * 60
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
                'access_token' => auth('web')->refresh(),
                'token_type' => 'bearer',
                'expires_in' => auth()->guard('web')->factory()->getTTL() * 60
            ],
            'status_code' => 200
        ]);
    }

    /**
     * @return JsonResponse
     */
    public function logout(): JsonResponse
    {
        auth('student')->logout();

        return response()->json(['message' => 'Successfully logged out']);
    }
}
