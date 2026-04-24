<?php

namespace App\Http\Controllers;

use App\Models\ParentModel;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ParentController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $query = ParentModel::with('user');

        if ($request->has('search')) {
            $search = $request->search;
            $query->whereHas('user', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            })->orWhere('parent_id', 'like', "%{$search}%");
        }

        $parents = $query->paginate(20);

        return $this->success($parents);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8',
            'parent_id' => 'required|string|unique:parents,parent_id',
            'phone' => 'nullable|string',
            'address' => 'nullable|string',
            'occupation' => 'nullable|string',
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => bcrypt($validated['password']),
            'role' => 'parent',
        ]);

        $parent = ParentModel::create([
            'user_id' => $user->id,
            'parent_id' => $validated['parent_id'],
            'phone' => $validated['phone'] ?? null,
            'address' => $validated['address'] ?? null,
            'occupation' => $validated['occupation'] ?? null,
        ]);

        return $this->success($parent->load('user'), 201);
    }

    public function show(ParentModel $parent): JsonResponse
    {
        return $this->success($parent->load('user'));
    }

    public function update(Request $request, ParentModel $parent): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'sometimes|string|max:255',
            'email' => 'sometimes|email|unique:users,email,' . $parent->user_id,
            'password' => 'sometimes|string|min:8',
            'phone' => 'nullable|string',
            'address' => 'nullable|string',
            'occupation' => 'nullable|string',
        ]);

        if (isset($validated['name']) || isset($validated['email']) || isset($validated['password'])) {
            $parent->user->update([
                'name' => $validated['name'] ?? $parent->user->name,
                'email' => $validated['email'] ?? $parent->user->email,
                'password' => isset($validated['password']) ? bcrypt($validated['password']) : $parent->user->password,
            ]);
        }

        $parent->update([
            'phone' => $validated['phone'] ?? $parent->phone,
            'address' => $validated['address'] ?? $parent->address,
            'occupation' => $validated['occupation'] ?? $parent->occupation,
        ]);

        return $this->success($parent->load('user'));
    }

    public function destroy(ParentModel $parent): JsonResponse
    {
        $parent->user()->delete();
        $parent->delete();

        return $this->success(['message' => 'Parent deleted successfully']);
    }
}
