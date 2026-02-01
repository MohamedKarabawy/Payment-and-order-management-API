<?php

namespace App\Http\Controllers;

use App\DTO\Auth\LoginDTO;
use App\DTO\Auth\RegisterDTO;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\JsonResponse;

class AuthController extends Controller
{
    public function register(RegisterRequest $request): JsonResponse
    {
        $userData = RegisterDTO::fromRequest($request->validated());

        $user = User::create($userData->toArray());

        $token = auth('api')->login($user);

        return response()->json([
            'message' => 'User Created Successfully',
            'user' => new UserResource($user),
            'token' => $this->respondWithToken($token)
        ], 201);
    }

    public function login(LoginRequest $request): JsonResponse
    {
        $credentials = LoginDTO::fromRequest($request->validated());

        if (!$token = auth('api')->attempt($credentials->toArray())) 
        {
            return response()->json(['error' => 'Email address or password is incorrect'], 401);
        }

        return response()->json([
            'message' => 'Logged in Successfully',
            'user' => new UserResource(auth('api')->user()),
            'token' => $this->respondWithToken($token)
        ]);
    }

    public function logout(): JsonResponse
    {
        auth('api')->logout();

        return response()->json(['message' => 'Logged out successfully']);
    }

    public function refresh(): JsonResponse
    {
        return response()->json([
            'message' => 'Token Refreshed Successfully',
            'refreshed_token' => $this->respondWithToken(auth('api')->refresh())
        ]);
    }

    protected function respondWithToken($token): array
    {
        return [
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth('api')->factory()->getTTL() * 60
        ];
    }
}
