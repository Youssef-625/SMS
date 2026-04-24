<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class StudentController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $query = Student::with('user');

        if ($request->has('search')) {
            $search = $request->search;
            $query->whereHas('user', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            })->orWhere('student_id', 'like', "%{$search}%");
        }

        $students = $query->paginate(20);

        return $this->success($students);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8',
            'student_id' => 'required|string|unique:students,student_id',
            'date_of_birth' => 'nullable|date',
            'gender' => 'nullable|in:male,female,other',
            'address' => 'nullable|string',
            'phone' => 'nullable|string',
            'enrollment_date' => 'nullable|date',
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => bcrypt($validated['password']),
            'role' => 'student',
        ]);

        $student = Student::create([
            'user_id' => $user->id,
            'student_id' => $validated['student_id'],
            'date_of_birth' => $validated['date_of_birth'] ?? null,
            'gender' => $validated['gender'] ?? null,
            'address' => $validated['address'] ?? null,
            'phone' => $validated['phone'] ?? null,
            'enrollment_date' => $validated['enrollment_date'] ?? null,
        ]);

        return $this->success($student->load('user'), 201);
    }

    public function show(Student $student): JsonResponse
    {
        return $this->success($student->load('user'));
    }

    public function update(Request $request, Student $student): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'sometimes|string|max:255',
            'email' => 'sometimes|email|unique:users,email,' . $student->user_id,
            'password' => 'sometimes|string|min:8',
            'date_of_birth' => 'nullable|date',
            'gender' => 'nullable|in:male,female,other',
            'address' => 'nullable|string',
            'phone' => 'nullable|string',
            'enrollment_date' => 'nullable|date',
        ]);

        if (isset($validated['name']) || isset($validated['email']) || isset($validated['password'])) {
            $student->user->update([
                'name' => $validated['name'] ?? $student->user->name,
                'email' => $validated['email'] ?? $student->user->email,
                'password' => isset($validated['password']) ? bcrypt($validated['password']) : $student->user->password,
            ]);
        }

        $student->update([
            'date_of_birth' => $validated['date_of_birth'] ?? $student->date_of_birth,
            'gender' => $validated['gender'] ?? $student->gender,
            'address' => $validated['address'] ?? $student->address,
            'phone' => $validated['phone'] ?? $student->phone,
            'enrollment_date' => $validated['enrollment_date'] ?? $student->enrollment_date,
        ]);

        return $this->success($student->load('user'));
    }

    public function destroy(Student $student): JsonResponse
    {
        $student->user()->delete();
        $student->delete();

        return $this->success(['message' => 'Student deleted successfully']);
    }
}
