<?php

namespace App\Http\Controllers;

use App\Models\Teacher;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TeacherController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $query = Teacher::with('user');

        if ($request->has('search')) {
            $search = $request->search;
            $query->whereHas('user', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            })->orWhere('teacher_id', 'like', "%{$search}%");
        }

        $teachers = $query->paginate(20);

        return $this->success($teachers);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8',
            'teacher_id' => 'required|string|unique:teachers,teacher_id',
            'date_of_birth' => 'nullable|date',
            'gender' => 'nullable|in:male,female,other',
            'address' => 'nullable|string',
            'phone' => 'nullable|string',
            'hire_date' => 'nullable|date',
            'qualification' => 'nullable|string',
            'subject_specialization' => 'nullable|string',
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => bcrypt($validated['password']),
            'role' => 'teacher',
        ]);

        $teacher = Teacher::create([
            'user_id' => $user->id,
            'teacher_id' => $validated['teacher_id'],
            'date_of_birth' => $validated['date_of_birth'] ?? null,
            'gender' => $validated['gender'] ?? null,
            'address' => $validated['address'] ?? null,
            'phone' => $validated['phone'] ?? null,
            'hire_date' => $validated['hire_date'] ?? null,
            'qualification' => $validated['qualification'] ?? null,
            'subject_specialization' => $validated['subject_specialization'] ?? null,
        ]);

        return $this->success($teacher->load('user'), 201);
    }

    public function show(Teacher $teacher): JsonResponse
    {
        return $this->success($teacher->load('user'));
    }

    public function update(Request $request, Teacher $teacher): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'sometimes|string|max:255',
            'email' => 'sometimes|email|unique:users,email,' . $teacher->user_id,
            'password' => 'sometimes|string|min:8',
            'date_of_birth' => 'nullable|date',
            'gender' => 'nullable|in:male,female,other',
            'address' => 'nullable|string',
            'phone' => 'nullable|string',
            'hire_date' => 'nullable|date',
            'qualification' => 'nullable|string',
            'subject_specialization' => 'nullable|string',
        ]);

        if (isset($validated['name']) || isset($validated['email']) || isset($validated['password'])) {
            $teacher->user->update([
                'name' => $validated['name'] ?? $teacher->user->name,
                'email' => $validated['email'] ?? $teacher->user->email,
                'password' => isset($validated['password']) ? bcrypt($validated['password']) : $teacher->user->password,
            ]);
        }

        $teacher->update([
            'date_of_birth' => $validated['date_of_birth'] ?? $teacher->date_of_birth,
            'gender' => $validated['gender'] ?? $teacher->gender,
            'address' => $validated['address'] ?? $teacher->address,
            'phone' => $validated['phone'] ?? $teacher->phone,
            'hire_date' => $validated['hire_date'] ?? $teacher->hire_date,
            'qualification' => $validated['qualification'] ?? $teacher->qualification,
            'subject_specialization' => $validated['subject_specialization'] ?? $teacher->subject_specialization,
        ]);

        return $this->success($teacher->load('user'));
    }

    public function destroy(Teacher $teacher): JsonResponse
    {
        $teacher->user()->delete();
        $teacher->delete();

        return $this->success(['message' => 'Teacher deleted successfully']);
    }
}
