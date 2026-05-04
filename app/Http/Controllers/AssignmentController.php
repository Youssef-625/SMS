<?php

namespace App\Http\Controllers;

use App\Models\Assignment;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AssignmentController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $query = Assignment::with(['classroom','subject','teacher']);

        if ($request->has('classroom_id')) {
            $query->where('classroom_id', $request->classroom_id);
        }

        if ($request->has('teacher_id')) {
            $query->where('teacher_id', $request->teacher_id);
        }

        $assignments = $query->orderBy('assigned_at','desc')->paginate(20);

        return $this->success($assignments);
    }

    public function store(Request $request): JsonResponse
    {
        $this->authorizeRequest();

        $validated = $request->validate([
            'classroom_id' => 'required|exists:classrooms,id',
            'subject_id' => 'required|exists:subjects,id',
            'teacher_id' => 'required|exists:teachers,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'assigned_at' => 'nullable|date',
            'due_at' => 'nullable|date',
            'points' => 'nullable|integer|min:1',
            'is_active' => 'boolean',
        ]);

        $assignment = Assignment::create($validated + ['assigned_at' => $validated['assigned_at'] ?? now()]);

        return $this->success($assignment, 'Assignment created', 201);
    }

    public function show(Assignment $assignment): JsonResponse
    {
        return $this->success($assignment->load(['submissions','classroom','subject','teacher']));
    }

    public function update(Request $request, Assignment $assignment): JsonResponse
    {
        $this->authorizeRequest();

        $validated = $request->validate([
            'title' => 'sometimes|string|max:255',
            'description' => 'nullable|string',
            'assigned_at' => 'nullable|date',
            'due_at' => 'nullable|date',
            'points' => 'nullable|integer|min:1',
            'is_active' => 'boolean',
        ]);

        $assignment->update($validated);

        return $this->success($assignment);
    }

    public function destroy(Assignment $assignment): JsonResponse
    {
        $this->authorizeRequest();
        $assignment->delete();
        return $this->success(null, 'Assignment deleted');
    }

    protected function authorizeRequest()
    {
        $user = auth()->user();
        if (!$user || !in_array($user->role, ['admin', 'super_admin', 'teacher'])) {
            abort(403, 'Forbidden');
        }
    }
}

