<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AttendanceController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $query = Attendance::with(['student.user', 'classroom']);

        if ($request->has('student_id')) {
            $query->where('student_id', $request->student_id);
        }

        if ($request->has('classroom_id')) {
            $query->where('classroom_id', $request->classroom_id);
        }

        if ($request->has('date')) {
            $query->where('date', $request->date);
        }

        $attendances = $query->orderBy('date', 'desc')->paginate(20);

        return $this->success($attendances);
    }

    public function store(Request $request): JsonResponse
    {
        // allow teachers and admins
        $this->authorizeRequest();

        $validated = $request->validate([
            'student_id' => 'required|exists:students,id',
            'classroom_id' => 'required|exists:classrooms,id',
            'date' => 'required|date',
            'status' => 'in:present,absent,late,excused',
            'notes' => 'nullable|string',
        ]);

        $attendance = Attendance::updateOrCreate(
            [
                'student_id' => $validated['student_id'],
                'classroom_id' => $validated['classroom_id'],
                'date' => $validated['date'],
            ],
            ['status' => $validated['status'] ?? 'present', 'notes' => $validated['notes'] ?? null]
        );

        return $this->success($attendance, 'Attendance recorded', 201);
    }

    public function show(Attendance $attendance): JsonResponse
    {
        return $this->success($attendance->load(['student.user', 'classroom']));
    }

    public function update(Request $request, Attendance $attendance): JsonResponse
    {
        $this->authorizeRequest();

        $validated = $request->validate([
            'status' => 'sometimes|in:present,absent,late,excused',
            'notes' => 'nullable|string',
        ]);

        $attendance->update($validated);

        return $this->success($attendance);
    }

    public function destroy(Attendance $attendance): JsonResponse
    {
        $this->authorizeRequest();
        $attendance->delete();
        return $this->success(null, 'Attendance deleted');
    }

    protected function authorizeRequest()
    {
        $user = auth()->user();
        if (!$user || !in_array($user->role, ['admin', 'super_admin', 'teacher'])) {
            abort(403, 'Forbidden');
        }
    }
}

