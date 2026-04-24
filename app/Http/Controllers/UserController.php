<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Responses\ApiResponse;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\ResponseTrait;
use function dd;


class UserController extends Controller
{
    use apiResponse;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return UserResource::collection(User::all());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreUserRequest $request)
    {
        $authUser = auth()->user();

        $requestedRole = $request->role; // e.g. admin, super_admin, teacher...

        // SUPER ADMIN RULE: can create anything
        if ($authUser->role !== 'super_admin') {

            // ADMIN RULE: cannot create admin or super_admin
            if ($authUser->role === 'admin') {

                if (in_array($requestedRole, ['admin', 'super_admin'])) {
                    return response()->json([
                        'message' => 'You are not allowed to create this role.'
                    ], 403);
                }
            }

            // optional: block other roles from creating high roles too
            else {
                if (in_array($requestedRole, ['admin', 'super_admin'])) {
                    return response()->json([
                        'message' => 'You are not allowed to create this role.'
                    ], 403);
                }
            }
        }

        $user = User::create($request->validated());
        return $this->success(new UserResource($user));
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        return $this->success(new UserResource($user));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateUserRequest $request, User $user)
    {
        $authUser = auth()->user();

        $requestedRole = $request->role; // e.g. admin, super_admin, teacher...
        if ($authUser->role !== 'super_admin') {

            // ADMIN RULE: cannot create admin or super_admin
            if ($authUser->role === 'admin') {

                if (in_array($requestedRole, ['admin', 'super_admin'])) {
                    return response()->json([
                        'message' => 'You are not allowed to update this User.'
                    ], 403);
                }
            }

            // optional: block other roles from creating high roles too
            else {
                if (in_array($requestedRole, ['admin', 'super_admin'])) {
                    return response()->json([
                        'message' => 'You are not allowed to update this user.'
                    ], 403);
                }
            }
        }

        $user->update($request->validated());
        return $this->success(new UserResource($user));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        if (auth()->user()->id === $user->id) {
            return $this->error('You cannot delete yourself', 403);
        }
        if ($user->role === 'super_admin') {
            return $this->error('You cannot delete a super admin', 403);
        }
        if ($user->role === 'admin' && auth()->user()->role !== 'super_admin') {
            return $this->error('You cannot delete an admin', 403);
        }
        $user->delete();
        return $this->success();
    }
}
