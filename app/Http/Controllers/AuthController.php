<?php

namespace App\Http\Controllers;

use App\Actions\Auth\LoginAction;
use App\Http\Requests\LoginRequest;
use Illuminate\Http\JsonResponse;
use function request;

class AuthController extends Controller
{
    public function login(LoginRequest $request, LoginAction $action)
    {
        $data = $action->execute($request);
        return $this->success($data);
    }

    public function me(): JsonResponse
    {
        $user = auth()->user();
        return $this->success([
            'id' => $user->id,
            'name' => $user->name,
            'role' => $user->role,
        ]);
    }

    public function logout(): JsonResponse
    {
        auth()->user()->currentAccessToken()->delete();

        return response()->json(['message' => 'Logged out']);
    }

    public function refresh()
    {
        return $this->success(request()->user());
    }
}
