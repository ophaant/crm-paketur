<?php

namespace App\Http\Controllers;

use App\Exceptions\IncorrectCredentialException;
use App\Http\Requests\LoginRequest;
use App\Traits\ApiResponseTrait;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;
use function PHPUnit\Framework\throwException;
use function Termwind\render;

class AuthenticationController extends Controller
{
    use ApiResponseTrait;
    public function login(LoginRequest $request)
    {
        $credentials = $request->safe()->only('email', 'password');

        try {
            if (!JWTAuth::attempt($credentials)) {
               return $this->error(config('rc.unauthenticated'));
            }

            // Get the authenticated user.
            $user = auth()->user();
            $token = JWTAuth::claims(['role' => $user->role])->fromUser($user);

            $response = [
                'access_token' => $token,
                'token_type' => 'bearer',
                'expires_in' => auth()->factory()->getTTL() * 60
            ];

            return $this->success($response,200);

        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return $this->error(config('rc.internal_server_error'));
        }
    }

    public function logout()
    {
        JWTAuth::invalidate(JWTAuth::getToken());

        return $this->success([],200,config('rc.logout_successfully'));
    }

    public function refresh()
    {
        $token = JWTAuth::refresh(JWTAuth::getToken());
        $response = [
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60
        ];

        return $this->success($response, 200);
    }
}
