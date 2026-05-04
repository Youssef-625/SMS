<?php

namespace App\Http\Controllers;

use App\Models\Exam;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ExamController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $query = Exam::with(['subject', 'classroom']);

        if ($request->has('classroom_id')) {
            $query->where('classroom_id', $request->classroom_id);
        }

        if ($request->has('subject_id')) {
            $query->where('subject_id', $request->subject_id);
        }

        $exams = $query->orderBy('scheduled_at', 'desc')->paginate(20);

        return $this->success($exams);
    }

    public function store(Request $request): JsonResponse
    {
        $this->authorizeRequest();

        $validated = $request->validate([
            'subject_id' => 'required|exists:subjects,id',
            'classroom_id' => 'required|exists:classrooms,id',
            'name' => 'required|string|max:255',
            'type' => 'in:quiz,midterm,final,other',
            'max_score' => 'integer|min:1',
            'scheduled_at' => 'nullable|date',
            'duration_minutes' => 'nullable|integer|min:1',
            'is_online' => 'boolean',
            'instructions' => 'nullable|string',
        ]);

        $exam = Exam::create($validated);

        return $this->success($exam, 'Exam created', 201);
    }

    public function show(Exam $exam): JsonResponse
    {
        return $this->success($exam->load(['subject','classroom','grades']));
    }

    public function update(Request $request, Exam $exam): JsonResponse
    {
        $this->authorizeRequest();

        $validated = $request->validate([
            'name' => 'sometimes|string|max:255',
            'type' => 'sometimes|in:quiz,midterm,final,other',
            'max_score' => 'sometimes|integer|min:1',
            'scheduled_at' => 'nullable|date',
            'duration_minutes' => 'nullable|integer|min:1',
            'is_online' => 'boolean',
            'instructions' => 'nullable|string',
        ]);

        $exam->update($validated);

        return $this->success($exam);
    }

    public function destroy(Exam $exam): JsonResponse
    {
        $this->authorizeRequest();
        $exam->delete();
        return $this->success(null, 'Exam deleted');
    }

    protected function authorizeRequest()
    {
        $user = auth()->user();
        if (!$user || !in_array($user->role, ['admin', 'super_admin', 'teacher'])) {
            abort(403, 'Forbidden');
        }
    }
}

