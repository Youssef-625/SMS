<?php

namespace App\Actions\Auth;

use App\Http\Requests\LoginRequest;
use App\Services\AuthService;
use function dump;

class LoginAction
{
    protected AuthService $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    public function execute(LoginRequest $request)
    {

        $user = $this->authService->attemptLogin(
            $request->input('email'),
            $request->input('password')
        );

        if (!$user) {
            abort(401, 'Invalid credentials');
        }
        $token = $this->authService->createToken($user);

        return [
            'token' => $token,
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'role' => $user->role,
            ],
        ];
    }
}
