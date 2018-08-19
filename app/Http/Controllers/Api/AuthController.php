<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\User;

class AuthController extends BaseController
{
    /**
     * Register user and return jwt
     *
     * @param RegisterRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(RegisterRequest $request)
    {
        User::create([
            'name' => $request->get('name'),
            'email' => $request->get('email'),
            'password' => bcrypt($request->get('password')),
        ]);

        if (! $token = auth()->attempt($request->only(['email', 'password']))) {
            return $this->jsonResponse(['Unauthorized'], false, 401);
        }

        return $this->respondWithToken($token);
    }

    /**
     * Get a JWT via given credentials.
     *
     * @param LoginRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(LoginRequest $request)
    {
        $credentials = $request->only(['email', 'password']);

        if (! $token = auth()->attempt($credentials)) {
            return $this->jsonResponse(['errors' => ['Unauthorized']], false, 401);
        }

        return $this->respondWithToken($token);
    }

    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function me()
    {
        return $this->jsonResponse(auth()->user());
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        auth()->logout();

        return $this->jsonResponse([]);
    }

    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        return $this->respondWithToken(auth('api')->refresh());
    }

    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token)
    {
        return $this->jsonResponse([
                'access_token' => $token,
                'token_type' => 'bearer',
                'expires_in' => auth()->factory()->getTTL() * 60
            ])
            ->header('Access-Control-Allow-Headers', 'Authorization')
            ->header('Access-Control-Expose-Headers', 'Authorization')
            ->header('Authorization', $token);
    }
}
